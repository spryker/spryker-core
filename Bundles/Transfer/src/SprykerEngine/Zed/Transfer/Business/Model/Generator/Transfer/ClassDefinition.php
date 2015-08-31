<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer;

use Zend\Filter\Word\UnderscoreToCamelCase;

class ClassDefinition implements ClassDefinitionInterface
{

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
    private $methods = [];

    /**
     * @var array
     */
    private $constructorDefinition = [];

    /**
     * @param array $definition
     *
     * @return $this
     */
    public function setDefinition(array $definition)
    {
        $this->setName($definition['name']);

        if (isset($definition['interface'])) {
            $this->addInterfaces($definition['interface']);
        }

        if (isset($definition['property'])) {
            $properties = $this->normalizePropertyTypes($definition['property']);
            $this->addProperties($properties);
            $this->addMethods($properties);
        }

        return $this;
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
            $this->addInterface($interface);
        }

        return $this;
    }

    /**
     * @param array $interface
     */
    private function addInterface(array $interface)
    {
        if (!in_array($interface['name'], $this->interfaces)) {
            $interfaceParts = explode('\\', $interface['name']);
            $name = array_pop($interfaceParts);
            $alias = $interface['bundle'] . $name;
            $this->uses[] = $interface['name'] . ' as ' . $alias;
            $this->interfaces[] = $alias;
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
        foreach ($properties as $property) {
            $this->addProperty($property);
        }
    }

    /**
     * @param array $property
     */
    private function addProperty(array $property)
    {
        $propertyInfo = [
            'name' => $property['name'],
            'type' => $this->getPropertyType($property),
        ];

        $this->properties[$property['name']] = $propertyInfo;
        $this->addUseForType($property);
        $this->addPropertyConstructorIfCollection($property);
    }

    /**
     * @param array $property
     */
    private function addUseForType(array $property)
    {
        if ($this->isCollection($property) && !$this->isArray($property)) {
            $transferName = ucfirst(str_replace('[]', '', $property['type']));
            if ($transferName !== $this->getName()) {
                $use = 'Generated\\Shared\\Transfer\\' . $transferName;
                $this->uses[$use] = $use;
            }
        }
    }

    /**
     * Properties which are Transfer MUST be suffixed with Transfer
     *
     * @param array $properties
     *
     * @return array
     */
    private function normalizePropertyTypes(array $properties)
    {
        $normalizedProperties = [];
        foreach ($properties as $property) {
            if (!preg_match('/^int|integer|string|array|bool|boolean/', $property['type'])) {
                if (preg_match('/\[\]$/', $property['type'])) {
                    $property['type'] = str_replace('[]', '', $property['type']) . 'Transfer[]';
                } else {
                    $property['type'] = $property['type'] . 'Transfer';
                }
            }
            $normalizedProperties[] = $property;
        }

        return $normalizedProperties;
    }

    /**
     * @param array $property
     *
     * @return string
     */
    private function getPropertyType(array $property)
    {
        if ($this->isArray($property)) {
            return 'array';
        }

        if ($this->isCollection($property)) {
            return '\ArrayObject|' . $property['type'];
        }

        return $property['type'];
    }

    /**
     * @param array $property
     *
     * @return string
     */
    private function getSetVar(array $property)
    {
        if ($this->isArray($property)) {
            return 'array';
        }

        if ($this->isCollection($property)) {
            return '\ArrayObject|' . $property['type'];
        }

        return $property['type'];
    }

    /**
     * @param array $property
     *
     * @return string
     */
    private function getAddVar(array $property)
    {
        if ($this->isArray($property)) {
            return 'array';
        } else {
            return str_replace('[]', '', $property['type']);
        }
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
        foreach ($properties as $property) {
            $this->addPropertyMethods($property);
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
            $this->constructorDefinition[$property['name']] = '\ArrayObject';
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
        $this->buildAddMethod($property);
    }

    /**
     * @param array $property
     */
    private function buildGetterAndSetter(array $property)
    {
        $this->buildSetMethod($property);
        $this->buildGetMethod($property);
    }

    /**
     * @param array $property
     *
     * @return string
     */
    private function getPropertyName(array $property)
    {
        $filter = new UnderscoreToCamelCase();

        return lcfirst($filter->filter($property['name']));
    }

    /**
     * @param array $property
     *
     * @return string
     */
    private function getReturnType(array $property)
    {
        if ($this->isArray($property)) {
            return 'array';
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
        return preg_match('/((.*?)\[\]|\[\]|array)/', $property['type']);
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    private function isArray(array $property)
    {
        return ($property['type'] === 'array' || $property['type'] === '[]');
    }

    /**
     * @param array $property
     *
     * @return bool|string
     */
    private function getTypeHint(array $property)
    {
        if ($this->isArray($property)) {
            return 'array';
        }

        if (preg_match('/(string|int|bool|boolean)/', $property['type'])) {
            return false;
        }

        if ($this->isCollection($property)) {
            return '\ArrayObject';
        }

        return $property['type'];
    }

    /**
     * @param array $property
     *
     * @return bool|string
     */
    private function getAddTypeHint(array $property)
    {
        if (preg_match('/^(string|int|bool|boolean|array|\[\])/', $property['type'])) {
            return false;
        } else {
            return str_replace('[]', '', $property['type']);
        }
    }

    /**
     * @param array $property
     */
    private function buildGetMethod(array $property)
    {
        $propertyName = $this->getPropertyName($property);
        $methodName = 'get' . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'return' => $this->getReturnType($property),
        ];
        $this->methods[$methodName] = $method;
    }

    /**
     * @param $property
     */
    private function buildSetMethod($property)
    {
        $propertyName = $this->getPropertyName($property);
        $methodName = 'set' . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'var' => $this->getSetVar($property),
        ];
        $method = $this->addTypeHint($property, $method);

        $this->methods[$methodName] = $method;
    }

    /**
     * @param $property
     */
    private function buildAddMethod($property)
    {
        $parent = $this->getPropertyName($property);
        if (array_key_exists('singular', $property)) {
            $property['name'] = $property['singular'];
        }
        $propertyName = $this->getPropertyName($property);
        $methodName = 'add' . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'parent' => $parent,
            'var' => $this->getAddVar($property),
        ];

        $typeHint = $this->getAddTypeHint($property);
        if ($typeHint) {
            $method['typeHint'] = $typeHint;
        }

        $this->methods[$methodName] = $method;
    }

    /**
     * @param array $property
     * @param array $method
     *
     * @return array
     */
    private function addTypeHint(array $property, array $method)
    {
        $typeHint = $this->getTypeHint($property);
        if ($typeHint) {
            $method['typeHint'] = $typeHint;
        }

        return $method;
    }

}
