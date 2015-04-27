<?php

namespace SprykerFeature\Zed\Transfer\Business\Model\Generator;

class ClassDefinition
{
    const TYPE_ARRAY    = 'array';
    const TYPE_STRING   = 'string';
    const TYPE_INTEGER  = 'int';
    const TYPE_OBJECT   = 'object';
    const TYPE_BOOL     = 'bool';

    protected $className;
    protected $interfaces = [];
    protected $properties = [];

    public function __construct($className)
    {
        $this->className = $className;
    }

    public function setInterface($implementsInterface)
    {

        if ( isset($implementsInterface[0]) ) {
            foreach ($implementsInterface as $newInterface) {
                $this->addInterface($newInterface);
            }
        } else {
            $this->addInterface($implementsInterface);
        }

        return $this;
    }

    protected function addInterface($interface)
    {
        if ( ! in_array($interface, $this->interfaces) ) {
            $this->interfaces[] = $interface['value'];
        }
    }


    public function setProperty(array $properties)
    {
        $this->properties[$properties['name']] = [
            'name' => $properties['name'],
            'type' => $this->getType($properties['type']),
            'default' => (isset($properties['default'])) ? $properties['default'] : '',
        ];
    }

    protected function getType($type)
    {
        if ( preg_match('/\[\]/', $type) ) {
            if ( $type === '[]' ) {
                return self::TYPE_ARRAY;
            }

            // this should be class type
            return strtr($type, array(
                '[]' => '',
            ));
        }

        return $type;
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
