<?php

namespace libc4group;

/**
 * Class C4GroupEntry
 * @package libc4group
 */
class C4GroupEntry {
    // attributes
    /**
     * @var
     */
    protected $filename;
    /**
     * @var
     */
    protected $isFolder;
    /**
     * @var
     */
    protected $fileSize;
    /**
     * @var
     */
    protected $dataOffset;
    /**
     * @var
     */
    protected $modified;
    /**
     * @var
     */
    protected $checksum;
    /**
     * @var
     */
    protected $executable;
    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $file;

    /**
     * @param $data
     */
    public function __construct(&$data) {
        if(count($data) != 316) {
            throw new Exception("C4GroupEntry size mismatch!");
        }
        // [0::257]: filename
        $this->setFilename(Data::parseStr($data, 0, 257));
        // [260::263]: int(val != 0)
        // assert(Data::parseInt($data, 260) != 0);
        // [264::267]: int(0 => File, 1=> Folder)
        $this->setIsFolder(1 === Data::parseInt($data, 264));
        // [268::271]: int(fileSize)
        $this->setFileSize(Data::parseInt($data, 268));
        // [276::279]: int(offset)
        $this->setDataOffset(Data::parseInt($data, 276));
        // [280::283]: int(modified)
        $this->setModified(Data::parseInt($data, 280));
        // [284::]: byte(checksum?)
        if(Data::parseBool($data, 284)) {
            // [285::288]
            $this->setChecksum(Data::parseInt($data, 285));
        }
        // [289::]: byte(executable?)
        $this->setExecutable(Data::parseBool($data, 289));
    }

    /**
     * @param mixed $checksum
     */
    public function setChecksum($checksum)
    {
        $this->checksum = $checksum;
    }

    /**
     * @return mixed
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * @param mixed $executable
     */
    public function setExecutable($executable)
    {
        $this->executable = $executable;
    }

    /**
     * @return mixed
     */
    public function getExecutable()
    {
        return $this->executable;
    }

    /**
     * @param mixed $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $dataOffset
     */
    public function setDataOffset($dataOffset)
    {
        $this->dataOffset = $dataOffset;
    }

    /**
     * @return mixed
     */
    public function getDataOffset()
    {
        return $this->dataOffset;
    }

    /**
     * @param mixed $isFolder
     */
    public function setIsFolder($isFolder)
    {
        $this->isFolder = $isFolder;
    }

    /**
     * @return mixed
     */
    public function getIsFolder()
    {
        return $this->isFolder;
    }

    /**
     * @param mixed $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    /**
     * @return mixed
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if(!$this->file) {
            $this->file = C4GroupFactory::getInstance($this);
        }
        return $this->file;
    }
} 