<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer;

use Zend\Filter\Word\CamelCaseToUnderscore;
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
    private $bundles = [];

    /**
     * @var array
     */
    private $constants = [];

    /**
     * @var array
     */
    private $properties = [];

    /**
     * @var array
     */
    private $normalizedProperties = [];

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
     * @return self
     */
    public function setDefinition(array $definition)
    {
        $this->setName($definition['name']);

        if (isset($definition['property'])) {
            $properties = $this->normalizePropertyTypes($definition['property']);
            $this->addConstants($properties);
            $this->addProperties($properties);
            $this->addMethods($properties);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return self
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

    private function addBundles(array $bundles)
    {
        foreach ($bundles as $bundle) {
            $this->addBundle($bundle);
        }
    }

    /**
     * @param string $bundle
     */
    private function addBundle($bundle)
    {
        if (!in_array($bundle, $this->bundles)) {
            $this->bundles[] = $bundle;
        }
    }

    /**
     * @return array
     */
    public function getBundles()
    {
        return $this->bundles;
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
    private function addConstants(array $properties)
    {
        foreach ($properties as $property) {
            $this->addConstant($property);
        }
    }

    /**
     * @param array $property
     */
    private function addConstant(array $property)
    {
        $property['name'] = lcfirst($property['name']);
        $propertyInfo = [
            'name' => $this->getPropertyConstantName($property),
            'value' => $property['name'],
        ];

        $this->constants[$property['name']] = $propertyInfo;
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
        $property['name'] = lcfirst($property['name']);
        $propertyInfo = [
            'name' => $property['name'],
            'type' => $this->getPropertyType($property),
            'bundles' => $property['bundles'],
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
            $property['type_fully_qualified'] = $property['type'];
            $property['is_collection'] = false;
            $property['is_transfer'] = false;
            $property['propertyConst'] = $this->getPropertyConstantName($property);
            $property['name_underscore'] = mb_strtolower($property['propertyConst']);

            if (!preg_match('/^int|integer|float|string|array|bool|boolean/', $property['type'])) {
                $property['is_transfer'] = true;
                $property['type_fully_qualified'] = 'Generated\\Shared\\Transfer\\';
                if (preg_match('/\[\]$/', $property['type'])) {
                    $property['type'] = str_replace('[]', '', $property['type']) . 'Transfer[]';
                    $property['type_fully_qualified'] .= str_replace('[]', '', $property['type']);
                    $property['is_collection'] = true;
                } else {
                    $property['type'] = $property['type'] . 'Transfer';
                    $property['type_fully_qualified'] .= $property['type'];
                }
            }

            $normalizedProperties[] = $property;
        }

        $this->normalizedProperties = $normalizedProperties;

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
        }

        return str_replace('[]', '', $property['type']);
    }

    /**
     * @return array
     */
    public function getConstants()
    {
        return $this->constants;
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
            $this->buildRequireMethod($property, true);
        } else {
            $this->buildGetterAndSetter($property);
            $this->buildRequireMethod($property, false);
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
     * @return array
     */
    public function getNormalizedProperties()
    {
        return $this->normalizedProperties;
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
    private function getPropertyConstantName(array $property)
    {
        $filter = new CamelCaseToUnderscore();

        return mb_strtoupper($filter->filter($property['name']));
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

        if (preg_match('/(string|int|float|bool|boolean)/', $property['type'])) {
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
        if (preg_match('/^(string|int|float|bool|boolean|array|\[\])/', $property['type'])) {
            return false;
        }

        return str_replace('[]', '', $property['type']);
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
            'propertyConst' => $this->getPropertyConstantName($property),
            'return' => $this->getReturnType($property),
            'bundles' => $property['bundles'],
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
            'propertyConst' => $this->getPropertyConstantName($property),
            'var' => $this->getSetVar($property),
            'bundles' => $property['bundles'],
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
        $propertyConstant = $this->getPropertyConstantName($property);
        if (array_key_exists('singular', $property)) {
            $property['name'] = $property['singular'];
        }
        $propertyName = $this->getPropertyName($property);
        $methodName = 'add' . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'propertyConst' => $propertyConstant,
            'parent' => $parent,
            'var' => $this->getAddVar($property),
            'bundles' => $property['bundles'],
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

    /**
     * @param array $property
     * @param bool $isCollection
     */
    private function buildRequireMethod(array $property, $isCollection)
    {
        $propertyName = $this->getPropertyName($property);
        $methodName = 'require' . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'propertyConst' => $this->getPropertyConstantName($property),
            'isCollection' => $isCollection,
            'bundles' => $property['bundles'],
        ];
        $this->methods[$methodName] = $method;
    }

}
