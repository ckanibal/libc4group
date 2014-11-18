<?php

namespace libc4group;

/**
 * Class Data
 * @package libc4group
 */
final class Data {
    /**
     * @param array $data
     * @param $begin
     * @param $length
     * @return string
     */
    public static function parseStr(array &$data, $begin, $length)
    {
        return trim(implode(array_map("chr", array_slice($data, $begin, $length))));
    }

    /**
     * @param array $data
     * @param $begin
     * @return int
     */
    public static function parseInt(array &$data, $begin)
    {
        $value = $data[$begin + 0] | ($data[$begin + 1] << 8) | ($data[$begin + 2] << 16) | ($data[$begin + 3] << 24);
        return $value;
    }

    /**
     * @param array $data
     * @param $index
     * @return bool
     */
    public static function parseBool(array &$data, $index) {
        return boolval($data[$index]);
    }
} 