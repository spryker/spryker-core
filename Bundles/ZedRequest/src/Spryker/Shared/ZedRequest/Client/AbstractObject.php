<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

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
            if (is_array($value) === false) {
                continue;
            }

            foreach ($value as $subKey => $subValue) {
                if (is_object($subValue) && method_exists($subValue, 'toArray')) {
                    /* @var ObjectInterface $subValue */
                    $value[$subKey] = $subValue->toArray(false);
                }
            }
            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * @param array $values
     *
     * @return void
     */
    public function fromArray(array $values)
    {
        $values = array_intersect_key($values, $this->values);
        $this->values = array_merge($this->values, $values);
    }

}
