<?php

namespace SprykerEngine\Shared\Transfer;

use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\Filter\Word\UnderscoreToCamelCase;

abstract class AbstractTransfer extends \ArrayObject implements TransferInterface
{

    /**
     * @var array
     */
    private $modifiedProperties = [];

    /**
     * @param string $property
     */
    protected function addModifiedProperty($property)
    {
        if (!in_array($property, $this->modifiedProperties)) {
            $this->modifiedProperties[] = $property;
        }
    }

    /**
     * @param bool $recursive
     *
     * @return array
     */
    public function toArray($recursive = true)
    {
        $values = [];
        $propertyNames = $this->getPropertyNames();

        $filter = new CamelCaseToUnderscore();
        foreach ($propertyNames as $property) {
            $getter = 'get' . ucfirst($property);
            $value = $this->$getter();

            $key = strtolower($filter->filter($property));

            if (is_object($value)) {
                if ($recursive && $value instanceof TransferInterface) {
                    $values[$key] = $value->toArray($recursive);
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
        foreach ($data as $key => $value) {
            $property = lcfirst($filter->filter($key));
            $getter = 'get' . ucfirst($property);
            $setter = 'set' . ucfirst($property);

            if (method_exists($this, $getter) && $this->$getter() instanceof TransferInterface && is_array($value)) {
                $this->$getter()->fromArray($value, $ignoreMissingProperty);
            } elseif (is_array($value)) {
                $this->$property = $value;
            } elseif (method_exists($this, $setter)) {
                $this->$setter($value);
            } elseif (!$ignoreMissingProperty) {
                throw new \InvalidArgumentException(
                    sprintf(
                        "Missing method or property in transfer object.\n [Transfer] %s\n[Property] %s",
                        get_class($this),
                        $property
                    )
                );
            }
        }

        return $this;
    }

    // @TODO check if we need this two methods
//    /**
//     * @return array
//     */
//    public function __sleep()
//    {
//        return array_keys($this->toArray(false, false));
//    }
//
//    public function __clone()
//    {
//        foreach (get_object_vars($this) as $key => $value) {
//            if (is_object($value)) {
//                $this->$key = clone $value;
//            }
//        }
//    }

    /**
     * @return array
     */
    private function getPropertyNames()
    {
        $classVars = get_class_vars(get_class($this));
        unset($classVars['modifiedProperties']);

        return array_keys($classVars);
    }

}
