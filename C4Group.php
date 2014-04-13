<?php

namespace libc4group;

require_once('Data.php');
require_once('C4GroupEntry.php');
require_once('C4GroupFactory.php');

/**
 * Class C4Group
 * @package libc4group
 */
class C4Group
{

    // header
    /**
     * @var
     */
    protected $author;
    /**
     * @var
     */
    protected $original;
    /**
     * @var
     */
    protected $numEntries;
    /**
     * @var
     */
    protected $created;
    /**
     * @var array
     */
    protected $entries = array();

    /**
     *
     */
    protected function __construct()
    {
    }

    /**
     * @param $data
     * @throws \Exception
     */
    protected function readBinary(&$data)
    {
        if(strlen($data) < 204) {
            throw new \Exception("Too few data!");
        }
        $this->data = $data;

        // ok then, parse the header
        $header = substr($this->data, 0, 204);
        $header = array_values(unpack("C*", $header));
        $this->memScramble($header);

        // [0..25]: RedWolf Design GrpFolder
        assert("RedWolf Design GrpFolder" === Data::parseStr($header, 0, 25));
        // [28::31]: int(1)
        assert(1 === Data::parseInt($header, 28));
        // [32::35]: int(2)
        assert(2 === Data::parseInt($header, 32));
        // [36::39]: int(childs)
        $this->setNumEntries(Data::parseInt($header, 36));
        // [40..71]: author
        $this->setAuthor(Data::parseStr($header, 40, 32));
        // [72::103]: reserved

        // [104::107]: int(created)
        $this->setCreated(Data::parseInt($header, 104));
        // [108::111]: int(original)
        $this->setOriginal(1234567 === Data::parseInt($header, 108));
        // [112::203]: reserved

        // ok then, parse the file entries
        for($i = 0; $i < $this->getNumEntries(); $i++) {
            $offset = 204 + $i * 316;
            $data = array_values(unpack("C*", substr($this->data, $offset, 316)));
            $entry = new C4GroupEntry($data);
            $dataOffset = 204 + $this->getNumEntries() * 316 + $entry->getDataOffset();
            $entry->setData(substr($this->data, $dataOffset, $entry->getFileSize()));
            $this->addEntry($entry);
        }
        $this -> data = null;
    }

    /**
     * @param $path
     * @return C4Group
     * @throws \Exception
     */
    public static function fromFile($path)
    {
        $data = file_get_contents($path);
        // fix magic bytes
        $data[0] = chr(0x1f);
        $data[1] = chr(0x8b);

        $data = gzdecode($data);
        if(!$data) {
            throw new \Exception("Could not gunzip data!");
        }
        if(false === $data) {
            throw new \Exception("Could not read file: " + $path);
        }
        return self::fromBinary($data);
    }

    /**
     * @param $data
     * @return C4Group
     * @throws \Exception
     */
    public static function fromBinary($data)
    {
        $instance = new self();
        $instance->readBinary($data);
        return $instance;
    }

    public function getEntry($name) {
        foreach($this->entries as &$entry) {
            if($entry->getFilename() === $name) {
                return $entry;
            }
        }
        // not found: exception
        throw new Exception("File not found!");
    }

    public function get($name) {
        return $this->getEntry($name)->get();
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $numChilds
     */
    public function setNumEntries($numChilds)
    {
        $this->numEntries = $numChilds;
    }

    /**
     * @return mixed
     */
    public function getNumEntries()
    {
        return $this->numEntries;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $original
     */
    public function setOriginal($original)
    {
        $this->original = $original;
    }

    /**
     * @return mixed
     */
    public function getOriginal()
    {
        return $this->original;
    }

    public function addEntry(C4GroupEntry $entry) {
        $this->entries[] = $entry;
    }

    /**
     *
     */
    public function __toString() {
        return "";
    }

    /**
     * Decrypts the group header
     * @param $data
     */
    private function memScramble(array &$data)
    {
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] ^= 237;
        }
        for ($i = 0; ($i + 2) < count($data); $i += 3) {
            $tmp = $data[$i];
            $data[$i] = $data[$i + 2];
            $data[$i + 2] = $tmp;
        }
    }
}