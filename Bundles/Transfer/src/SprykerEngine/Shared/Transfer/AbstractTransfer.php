<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Transfer;

use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Reflection\MethodReflection;
use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\Filter\Word\UnderscoreToCamelCase;

abstract class AbstractTransfer extends \ArrayObject implements TransferInterface
{

    /**
     * @var array
     */
    private $modifiedProperties = [];

    /**
     * @param bool $recursive
     *
     * @return array
     */
    public function toArray($recursive = true)
    {
        $values = [];
        $propertyNames = $this->getPropertyNames();

        $recursive = true;
        $filter = new CamelCaseToUnderscore();
        foreach ($propertyNames as $property) {
            $getter = 'get' . ucfirst($property);
            $value = $this->$getter();

            $key = strtolower($filter->filter($property));

            if (is_object($value)) {
                if ($recursive && $value instanceof TransferInterface) {
                    $values[$key] = $value->toArray($recursive);
                } elseif ($recursive && $value instanceof \ArrayObject && count($value) >= 1) {
                    foreach ($value as $elementKey => $arrayElement) {
                        $values[$key][$elementKey] = $arrayElement->toArray($recursive);
                    }
                } else {
                    $values[$key] = $value;
                }
                continue;
            }

            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * @return array
     */
    private function getPropertyNames()
    {
        $classVars = get_class_vars(get_class($this));
        unset($classVars['modifiedProperties']);

        return array_keys($classVars);
    }

    /**
     * @param bool $recursive
     *
     * @return array
     */
    public function modifiedToArray($recursive = true)
    {
        $returnData = [];
        foreach ($this->modifiedProperties as $modifiedProperty) {
            $key = $modifiedProperty;
            $getterName = 'get' . ucfirst($modifiedProperty);
            $value = $this->$getterName();
            if (is_object($value)) {
                if ($recursive && $value instanceof TransferInterface) {
                    $returnData[$key] = $value->modifiedToArray($recursive);
                } else {
                    $returnData[$key] = $value;
                }
            } else {
                $returnData[$key] = $value;
            }
        }

        return $returnData;
    }

    /**
     * @param array $data
     * @param bool $ignoreMissingProperty
     *
     * @return $this
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        $filter = new UnderscoreToCamelCase();
        $properties = $this->getPropertyNames();
        foreach ($data as $key => $value) {
            $property = lcfirst($filter->filter($key));

            if (!in_array($property, $properties)) {
                if ($ignoreMissingProperty) {
                    continue;
                } else {
                    throw new \InvalidArgumentException(
                        sprintf('Missing property "%s" in "%s"', $property, get_class($this))
                    );
                }
            }

            $getter = 'get' . ucfirst($property);
            $getterReturn = $this->getGetterReturn($getter);
            $setter = 'set' . ucfirst($property);
            $type = $this->getSetterType($setter);

            // Process Array
            if (is_array($value) && $this->isArray($getterReturn) && $type === 'ArrayObject') {
                /* @var TransferInterface $transferObject */
                $elementType = $this->getArrayTypeWithNamespace($getterReturn);
                $transferObjectsArray = new \ArrayObject();
                foreach ($value as $arrayElement) {
                    $transferObject = new $elementType();
                    if (is_array($arrayElement)) {
                        $transferObject->fromArray($arrayElement);
                    }
                    $transferObjectsArray->append($transferObject);
                }
                $value = $transferObjectsArray;
            }

            // Process nested Transfer Objects
            if ($this->isTransferClass($type)) {
                /** @var TransferInterface $transferObject */
                $transferObject = new $type();
                if (is_array($value)) {
                    $transferObject->fromArray($value);
                    $value = $transferObject;
                }
            }

            try {
                $this->$setter($value);
            } catch (\Exception $e) {
                if ($ignoreMissingProperty) {
                    throw new \InvalidArgumentException(
                        sprintf('Missing property "%s" in "%s"', $property, get_class($this))
                    );
                }
            }
        }

        return $this;
    }

    /**
     * @param string $getterMethod
     *
     * @return string
     */
    private function getGetterReturn($getterMethod)
    {
        $reflection = new MethodReflection(get_class($this), $getterMethod);

        /** @var ReturnTag $return */
        $return = $reflection->getDocBlock()->getTag('return');

        return $return->getTypes()[0];
    }

    /**
     * @param string $setter
     *
     * @return string
     */
    private function getSetterType($setter)
    {
        $reflection = new MethodReflection(get_class($this), $setter);

        return $reflection->getParameters()[0]->getType();
    }

    /**
     * @param string $returnType
     *
     * @return bool
     */
    private function isArray($returnType)
    {
        return strpos($returnType, '[]') > -1;
    }

    /**
     * @param string $returnType
     *
     * @return string
     */
    private function getArrayTypeWithNamespace($returnType)
    {
        return 'Generated\\Shared\\Transfer\\' . str_replace('[]', '', $returnType);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function isTransferClass($type)
    {
        if (!is_string($type)) {
            return false;
        }

        $name = explode('\\', $type);

        return (
            count($name) > 3 &&
            $name[0] === 'Generated' &&
            $name[1] === 'Shared' &&
            $name[2] === 'Transfer'
        );
    }

    /**
     * @param string $property
     */
    protected function addModifiedProperty($property)
    {
        if (!in_array($property, $this->modifiedProperties)) {
            $this->modifiedProperties[] = $property;
        }
    }

}
