<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\ZedRequest\Client;

abstract class AbstractObject implements ObjectInterface
{
    /**
     * @var array
     */
    protected $values;

    /**
     * @param array $values
     */
    public function __construct(array $values = null)
    {
        if (!empty($values)) {
            $this->fromArray($values);
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $values = $this->values;

        foreach ($values as $key => $value) {
            if ($value === null || is_array($value) && empty($value)) {
                unset($values[$key]);
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    if ($subValue === null || is_array($subValue) && empty($subValue)) {
                        unset($value[$subKey]);
                        continue;
                    }

                    if (is_object($subValue) && method_exists($subValue, 'toArray')) {
                        /** @var ObjectInterface $subValue */
                        $value[$subKey] = $subValue->toArray(false);
                    }
                }
                if (empty($value)) {
                    unset($values[$key]);
                } else {
                    $values[$key] = $value;
                }
                continue;
            }
        }
        return $values;
    }

    /**
     * @param array $values
     */
    public function fromArray(array $values)
    {
        $values = array_intersect_key($values, $this->values);
        $this->values = array_merge($this->values, $values);
    }
}
