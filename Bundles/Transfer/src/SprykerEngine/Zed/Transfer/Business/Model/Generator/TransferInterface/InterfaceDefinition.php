<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use Zend\Filter\Word\UnderscoreToCamelCase;

class InterfaceDefinition implements InterfaceDefinitionInterface
{

    /**
     * @var string
     */
    private $bundle;

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
        $this->setBundle($definition);
        $this->setName($definition);

        if (isset($definition['property'])) {
            $properties = $this->normalizePropertyTypes($definition['property']);
            $this->addProperties($properties);
            $this->addMethods($properties);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return 'Generated\\Shared\\' . $this->getBundle();
    }

    /**
     * @param array $definition
     */
    public function setBundle(array $definition)
    {
        $this->bundle = $definition['bundle'];
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param array $definition
     *
     * @return $this
     */
    private function setName(array $definition)
    {
        $name = $definition['name'];
        if (strpos($name, 'Interface') === false) {
            $name .= 'Interface';
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
        if ($this->isTransfer($property)) {
            $use = 'Generated\\Shared\\Transfer\\' . str_replace('[]', '', $property['type']);
            $this->uses[$use] = $use;
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
     * @return int
     */
    private function isTransfer(array $property)
    {
        return !preg_match('/^int|integer|string|array|bool|boolean/', $property['type']);
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
