<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

use Zend\Filter\Word\UnderscoreToCamelCase;

class ClassDefinition implements ClassDefinitionInterface
{

    const TYPE_ARRAY = 'array';
    const TYPE_BOOLEAN = 'bool';
    const TYPE_INTEGER = 'int';
    const TYPE_STRING = 'string';

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $uses = [];

    /**
     * @var array
     */
    private $interfaces = [];

    /**
     * @var array
     */
    private $properties = [];

    /**
     * @var array
     */
    private $constructorDefinition = [];

    /**
     * @var array
     */
    private $methods = [];

    /**
     * @param array $transferDefinition
     */
    public function __construct(array $transferDefinition)
    {
        $this->setName($transferDefinition['name']);

        if (isset($transferDefinition['interface'])) {
            $this->addInterfaces($transferDefinition['interface']);
        }

        if (isset($transferDefinition['use'])) {
            $this->addUses($transferDefinition['use']);
        }

        if (isset($transferDefinition['property'])) {
            $this->addProperties($transferDefinition['property']);
            $this->addMethods($transferDefinition['property']);
        }
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    private function setName($name)
    {
        if (strpos($name, 'Transfer') === false) {
            $name .= 'Transfer';
        }
        $this->name = ucfirst($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $interfaces
     *
     * @return $this
     */
    private function addInterfaces(array $interfaces)
    {
        foreach ($interfaces as $interface) {
            if (is_array($interface)) {
                $this->addInterface($interface['name']);
            } else {
                $this->addInterface($interface);
            }
        }

        return $this;
    }

    /**
     * @param string $interface
     */
    private function addInterface($interface)
    {
        if (!in_array($interface, $this->interfaces)) {
            $this->interfaces[] = $interface;
        }
    }

    /**
     * @return array
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @param array $uses
     */
    private function addUses(array $uses)
    {
        foreach ($uses as $use) {
            if (is_array($use)) {
                $this->addUse($use['name']);
            } else {
                $this->addUse($use);
            }
        }
    }

    /**
     * @param array $use
     */
    private function addUse($use)
    {
        if (!in_array($use, $this->uses)) {
            $this->uses[] = $use;
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
     * @param array $properties
     */
    private function addProperties(array $properties)
    {
        if (isset($properties[0])) {
            foreach ($properties as $property) {
                $this->addProperty($property);
            }
        } else {
            $this->addProperty($properties);
        }
    }

    /**
     * @param array $property
     */
    private function addProperty(array $property)
    {
        $propertyInfo = [
            'name' => $property['name'],
            'type' => $this->getType($property),
            'default' => (isset($property['default'])) ? $property['default'] : ''
        ];

        $this->properties[$property['name']] = $propertyInfo;
        $this->addPropertyConstructorIfCollection($property);
    }

    /**
     * @param array $property
     * @param string|null $methodPrefix
     *
     * @return string
     */
    private function getType(array $property, $methodPrefix = null)
    {
        if (!is_null($methodPrefix) && array_key_exists($methodPrefix, $property) && array_key_exists('type', $property[$methodPrefix])) {
            return $property[$methodPrefix]['type'];
        }
        if ($property['type'] === '[]' || $property['type'] === 'array') {
            return 'array';
        }

        if (isset($property['collection'])) {
            return $property['type'];
        }

        return $property['type'];
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    private function addMethods(array $properties)
    {
        if (isset($properties[0])) {
            foreach ($properties as $property) {
                $this->addPropertyMethods($property);
            }
        } else {
            $this->addPropertyMethods($properties);
        }
    }

    /**
     * @param array $property
     */
    private function addPropertyMethods(array $property)
    {
        if ($this->isCollection($property)) {
            $this->buildCollectionMethods($property);
        } else {
            $this->buildGetterAndSetter($property);
        }
    }

    /**
     * @param array $property
     */
    private function addPropertyConstructorIfCollection(array $property)
    {
        if ($this->isCollection($property)) {
            $this->constructorDefinition[$property['name']] = (is_string($property['collection'])) ? $property['collection'] : 'Collection';
        }
    }

    /**
     * @return array
     */
    public function getConstructorDefinition()
    {
        return $this->constructorDefinition;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param array $property
     */
    private function buildCollectionMethods(array $property)
    {
        $this->buildGetterAndSetter($property);
        $this->buildMethod($property, 'add');
        $this->buildMethod($property, 'remove');
        $this->buildMethod($property, 'has');
    }

    /**
     * @param array $property
     */
    private function buildGetterAndSetter(array $property)
    {
        $this->buildMethod($property, 'set');
        $this->buildGetMethod($property);
    }

    /**
     * @param array $property
     * @param $methodPrefix
     *
     * @return string
     */
    private function getPropertyName(array $property, $methodPrefix)
    {
        if (array_key_exists($methodPrefix, $property) && array_key_exists('name' , $property[$methodPrefix])) {
            $propertyName = $property[$methodPrefix]['name'];
        } else {
            $propertyName = $property['name'];
        }

        $filter = new UnderscoreToCamelCase();

        return lcfirst($filter->filter($propertyName));
    }

    /**
     * @param array $property
     *
     * @return string
     */
    private function getReturnType(array $property)
    {
        if ($property['type'] === 'array' || $property['type'] === '[]') {
            return 'array';
        }

        if (isset($property['collection'])) {
            return $property['type'];
        }

        return $property['type'];
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    private function isCollection(array $property)
    {
        return isset($property['collection']) && !empty($property['collection']);
    }

    /**
     * @param array $property
     * @param $methodPrefix
     *
     * @return bool|string
     */
    private function getTypeHint(array $property, $methodPrefix)
    {
        if (array_key_exists($methodPrefix, $property) && array_key_exists('type' , $property[$methodPrefix])) {
            if (preg_match('/(string|int|bool|boolean)/', $property[$methodPrefix]['type'])) {
                return false;
            }

            return $property[$methodPrefix]['type'];
        }

        if ($property['type'] === 'array' || $property['type'] === '[]') {
            return 'array';
        }

        if (preg_match('/(string|int|bool|boolean)/', $property['type'])) {
            return false;
        }

        return $property['type'];
    }

    /**
     * @param array $property
     */
    private function buildGetMethod(array $property)
    {
        $propertyName = $this->getPropertyName($property, 'get');
        $methodName = 'get' . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'return' => $this->getReturnType($property)
        ];
        $this->methods[$methodName] = $method;
    }

    /**
     * @param $property
     * @param $prefix
     */
    private function buildMethod($property, $prefix)
    {
        $propertyName = $this->getPropertyName($property, $prefix);
        $methodName = $prefix . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'var' => $this->getType($property, $prefix),
        ];
        $method = $this->addTypeHint($property, $method, $prefix);
        $method = $this->addDefault($property, $method, $prefix);
        $method = $this->addParentIfNeeded($property, $method, $prefix);

        $this->methods[$methodName] = $method;
    }

    /**
     * @param array $property
     * @param array $method
     * @param string $prefix
     *
     * @return array
     */
    private function addTypeHint(array $property, array $method, $prefix)
    {
        $typeHint = $this->getTypeHint($property, $prefix);
        if ($typeHint) {
            $method['typeHint'] = $typeHint;
        }

        return $method;
    }

    /**
     * @param array $property
     * @param array $method
     * @param string $prefix
     *
     * @return array
     */
    private function addParentIfNeeded(array $property, array $method,  $prefix)
    {
        if (array_key_exists($prefix, $property)) {
            if (array_key_exists('parent', $property[$prefix])) {
                $method['parent'] = $property[$prefix]['parent'];
            } else {
                $method['parent'] = $property['name'];
            }
        }

        if (!array_key_exists('parent', $method) && array_key_exists('collection', $property)) {
            $method['parent'] = $property['name'];
        }

        return $method;
    }

    /**
     * @param array $property
     * @param array $method
     * @param string $prefix
     *
     * @return array
     */
    private function addDefault(array $property, array $method,  $prefix)
    {
        if (array_key_exists('default', $property)) {
            $method['default'] = $property['default'];
        }

        return $method;
    }
}
