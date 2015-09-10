<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payone\ClientApi\Request;

abstract class AbstractContainer implements ContainerInterface
{

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];
        foreach ($this as $key => $value) {
            if ($value === null) {
                continue;
            }
            if ($value instanceof ContainerInterface) {
                $result = array_merge($result, $value->toArray());
            } else {
                $result[$key] = $value;
            }
        }
        ksort($result);

        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $stringArray = [];
        foreach ($this->toArray() as $key => $value) {
            $stringArray[] = $key . '=' . $value;
        }
        $result = implode('|', $stringArray);

        return $result;
    }

}
