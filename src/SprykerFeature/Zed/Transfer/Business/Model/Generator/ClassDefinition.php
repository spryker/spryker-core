<?php

namespace SprykerFeature\Zed\Transfer\Business\Model\Generator;

class ClassDefinition
{
    const TYPE_ARRAY    = 'array';
    const TYPE_BOOLEAN  = 'boolean';

    protected $className;
    protected $interfaces = [];
    protected $properties = [];

    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @param array|string $implementsInterface
     * @return $this
     */
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

    /**
     * Add interface to list if it doesn't exists already
     *
     * @param string $interface
     */
    protected function addInterface($interface)
    {
        if ( ! in_array($interface, $this->interfaces) ) {
            $this->interfaces[] = $interface['value'];
        }
    }

    /**
     * @param array $properties
     */
    public function setProperty(array $properties)
    {
        $this->properties[$properties['name']] = [
            'name' => $properties['name'],
            'type' => $this->getType($properties['type']),
            'default' => (isset($properties['default'])) ? $properties['default'] : '',
        ];
    }

    /**
     * @param string $type
     * @return string
     */
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

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return array
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
