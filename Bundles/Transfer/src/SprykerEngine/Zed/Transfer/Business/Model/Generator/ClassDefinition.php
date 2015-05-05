<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class ClassDefinition implements ClassDefinitionInterface
{
    const TYPE_ARRAY = 'array';
    const TYPE_BOOLEAN = 'bool';
    const TYPE_INTEGER = 'int';
    const TYPE_STRING = 'string';

    /**
     * @var string
     */
    protected $className;

    /**
     * @var array
     */
    protected $interfaces = [];

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @var array
     */
    protected $uses = [];

    /**
     * @var array
     */
    protected $needsConstructor = [];

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->setClassName($className);
    }

    /**
     * @param array|string $implementsInterface
     * @return $this
     */
    public function setInterface($implementsInterface)
    {
        if (is_array($implementsInterface)) {
            foreach ($implementsInterface as $newInterface) {
                $this->setInterface($newInterface);
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
        if (!in_array($interface, $this->interfaces)) {
            $this->interfaces[] = $interface;
        }
    }

    /**
     * @param array|string $uses
     */
    public function setUses($uses)
    {
        if (is_array($uses)) {
            foreach ($uses as $useStatement) {
                $this->addUses($useStatement);
            }
        } else {
            $this->addUses($uses);
        }
    }

    /**
     * @param string $useStatement
     */
    protected function addUses($useStatement)
    {
        if (!in_array($useStatement, $this->uses)) {
            $this->uses[] = $useStatement;
        }
    }

    /**
     * @return array
     */
    public function getUses()
    {
        return $this->uses;
    }

    /**
     * @param array $property
     */
    public function setProperty(array $property)
    {
        $propertyInfo = [
            'name' => $property['name'],
            'type_special' => $this->checkTypeSpecial($property),
            'type' => $this->getType($property['type']),
            'default' => (isset($property['default'])) ? $property['default'] : '',
            'collection' => (isset($property['collection'])) ? $property['collection'] : '',
        ];

        $this->properties[$property['name']] = $propertyInfo;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getType($type)
    {
        if ($this->isTypeSpecial($type) && $type === '[]') {
            return self::TYPE_ARRAY;
        }

        return $type;
    }

    /**
     * @param string $type
     * @return bool
     */
    protected function isTypeSpecial($type)
    {
        return (bool) preg_match('/\[\]/', $type);
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    protected function checkTypeSpecial(array $property)
    {
        if ($this->isTypeSpecial($property['type']) || isset($property['collection'])) {
            $this->setPropertiesThatNeedsConstructor($property);

            return true;
        }

        return false;
    }

    /**
     * Transfer objects should have Transfer word in they're name
     * append it if not
     *
     * @param string $name
     */
    public function setClassName($name)
    {
        if (strpos($name, 'Transfer') === false) {
            $name .= 'Transfer';
        }
        $this->className = $name;
    }

    /**
     * @param array $property
     */
    private function setPropertiesThatNeedsConstructor(array $property)
    {
        $this->needsConstructor[$property['name']] = (is_string($property['collection'])) ? $property['collection'] : 'Collection';
    }

    /**
     * @return array
     */
    public function getNeedsConstructor()
    {
        return $this->needsConstructor;
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
