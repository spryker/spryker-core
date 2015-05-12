<?php

namespace SprykerEngine\Shared\Transfer;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Library\Filter\CamelCaseToSeparatorFilter;
use SprykerFeature\Shared\Library\Filter\FilterChain;
use SprykerFeature\Shared\Library\Filter\SeparatorToCamelCaseFilter;
use Zend\Filter\Word\CamelCaseToUnderscore;

abstract class AbstractTransfer extends \ArrayObject implements TransferInterface
{

    /**
     * @var array
     */
    private $modifiedProperties = [];

    public function __construct()
    {
        parent::__construct([], \ArrayObject::STD_PROP_LIST);
    }

    /**
     * @param bool $includeNullValues
     * @param bool $recursive
     *
     * @return array
     */
    public function toArray($includeNullValues = true, $recursive = true)
    {
        $varsForArray = [];

        $classVars = get_object_vars($this);
        $filter = new CamelCaseToUnderscore();
        foreach ($classVars as $name => $value) {
            if ($name === 'modifiedProperties') {
                continue;
            }

            $key = strtolower($filter->filter($name));

            if (is_null($value)) {
                if ($includeNullValues) {
                    $varsForArray[$key] = $value;
                }
                continue;
            }

            if (is_scalar($value)) {
                $varsForArray[$key] = $value;
                continue;
            }

            if (is_object($value)) {
                if ($recursive && $value instanceof TransferInterface) {
                    $varsForArray[$key] = $value->toArray($includeNullValues, $recursive);
                } else {
                    $varsForArray[$key] = $value;
                }
                continue;
            }

            if (is_array($value)) {
                $varsForArray[$key] = $value;
                continue;
            }
        }

        return $varsForArray;
    }

    /**
     * @param bool $recursive
     *
     * @return array
     */
    public function modifiedToArray($recursive = true)
    {
        $returnData = [];
        $modifiedProperties = $this->getModifiedProperties();
        foreach ($modifiedProperties as $modifiedProperty) {
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
     * @param string $property
     */
    protected function addModifiedProperty($property)
    {
        if (!in_array($property, $this->modifiedProperties)) {
            $this->modifiedProperties[] = $property;
        }
    }

    /**
     * @return array
     */
    protected function getModifiedProperties()
    {
        return $this->modifiedProperties;
    }

    /**
     * @param array $data
     * @param bool $fuzzyMatch
     *
     * @return $this
     */
    public function fromArray(array $data, $fuzzyMatch = false)
    {
        foreach ($data as $key => $value) {
            $property = lcfirst($key);
            $getter = 'get' . $key;
            $setter = 'set' . $key;

            if (method_exists($this, $getter) && $this->$getter() instanceof TransferInterface && is_array($value)) {
                $this->$getter()->fromArray($value, $fuzzyMatch);
            } elseif (is_array($value)) {
                $this->$property = $value;
            } elseif (method_exists($this, $setter)) {
                $this->$setter($value);
            } elseif (!$fuzzyMatch) {
                throw new \LogicException(
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

    /**
     * @return array
     */
    public function __sleep()
    {
        return array_keys($this->toArray(false, false));
    }

    public function __clone()
    {
        foreach (get_object_vars($this) as $key => $value) {
            if (is_object($value)) {
                $this->$key = clone $value;
            }
        }
    }

}
