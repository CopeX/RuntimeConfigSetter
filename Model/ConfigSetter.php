<?php

namespace CopeX\RuntimeConfigSetter\Model;

use Magento\Config\App\Config\Type\System;

class ConfigSetter
{

    /**
     * @var System
     */
    private $systemConfig;

    public function __construct(System $systemConfig)
    {
        $this->systemConfig = $systemConfig;
    }

    public function setConfigValue($path, $value){
        $paths = explode("/", $path);
        if( count($paths) === 3){
            $reflection = new \ReflectionClass($this->systemConfig);
            $property = $reflection->getProperty('data');
            $property->setAccessible(true);
            $data = $property->getValue($this->systemConfig);
            $this->setConfigValueToData($data, $paths, $value);
            $property->setValue($this->systemConfig, $data);
        }
        else {
            throw new \Exception("Please provide full config path");
        }
    }

    private function setConfigValueToData(&$data, $path, $value)
    {
        $data['default'][$path[0]][$path[1]][$path[2]] = $value;
        foreach ($data['websites'] ?? [] as $key => $website) {
            $data['websites'][$key][$path[0]][$path[1]][$path[2]] = $value;
        }
        foreach ($data['stores'] ?? [] as $key => $website) {
            $data['stores'][$key][$path[0]][$path[1]][$path[2]] = $value;
        }
    }

}