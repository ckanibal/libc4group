<?php

namespace libc4group;

/**
 * Class C4Scenario
 * @package libc4group
 */
class C4Scenario extends C4Group {
    /**
     * @return mixed
     */
    public function getTitleImage() {
        return $this->get('Title.png');
    }

    /**
     * @param $data
     * @return C4Group|C4Scenario
     */
    public static function fromBinary($data)
    {
        $instance = new self();
        $instance->readBinary($data);
        return $instance;
    }
}
