<?php

namespace libc4group;

/**
 * Class C4GroupFactory
 * @package libc4group
 */
final class C4GroupFactory {
    /**
     * NO instances!
     */
    private function __construct() {
    }

    /**
     * @param C4GroupEntry $entry
     * @return C4Group|C4Scenario|mixed
     */
    public static function getInstance(C4GroupEntry &$entry) {
        // TODO: implement proper subclasses
        switch(strtolower(pathinfo($entry->getFilename(), PATHINFO_EXTENSION))) {
            case "c4s":
                return C4Scenario::fromBinary($entry->getData());
            case "c4f":
                return C4Group::fromBinary($entry->getData());
            case "c4d":
                return C4Group::fromBinary($entry->getData());
            case "c4g":
                return C4Group::fromBinary($entry->getData());
            default:
                return $entry->getData();
        }
    }
} 
