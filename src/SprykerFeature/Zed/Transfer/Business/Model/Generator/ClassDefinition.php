<?php

namespace SprykerFeature\Zed\Transfer\Business\Model\Generator;

class ClassDefinition
{
    protected $className;
    protected $interfaces = [];
    protected $properties = [];

    public function __construct($className)
    {
        $this->className = $className;
    }

    public function setInterface($implementsInterface)
    {
        if ( ! empty($implementsInterface) ) {
            $this->interfaces[] = $implementsInterface;
        }

        return $this;
    }

    public function setProperty(array $properties)
    {
        $this->properties[$properties['name']] = [
            'name' => $properties['name'],
            'type' => $properties['type'],
            'default' => (isset($properties['default'])) ? $properties['default'] : '',
        ];
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getInterfaces()
    {
        return $this->interfaces;
    }

    public function getProperties()
    {
        return $this->properties;
    }
}
