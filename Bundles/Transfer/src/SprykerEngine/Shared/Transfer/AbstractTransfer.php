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

            if (method_exists($this, $setter)) {
                if ($this->canSetValue($setter, $value)) {
                    $this->$setter($value);
                }
            } elseif (!$ignoreMissingProperty) {
                throw new \InvalidArgumentException(
                    sprintf('Missing property "%s" in "%s"', $property, get_class($this))
                );
            }
        }

        return $this;
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
     * If fromArray() tries to set a transfer object to something else than its allowed, an exception is thrown.
     *
     * To prevent that en error is thrown when calling e.g.
     * setTransfer(TransferInterface $transferInterface) with null or string etc
     * This code is wrapped in a try catch block
     *
     * @param $setter
     * @param $value
     *
     * @return bool
     */
    private function canSetValue($setter, $value)
    {
        try {
            $this->$setter($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}
