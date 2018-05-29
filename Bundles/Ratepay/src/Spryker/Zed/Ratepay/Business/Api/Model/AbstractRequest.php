<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model;

use Spryker\Zed\Ratepay\Business\Api\Builder\BuilderInterface;
use Spryker\Zed\Ratepay\Business\Api\SimpleXMLElement;

abstract class AbstractRequest implements RequestInterface
{
    /**
     * @return array
     */
    abstract protected function buildData();

    /**
     * @return string
     */
    abstract public function getRootTag();

    /**
     * @param array $array
     * @param \Spryker\Zed\Ratepay\Business\Api\SimpleXMLElement $xml
     *
     * @return void
     */
    protected function arrayToXml(array $array, SimpleXMLElement &$xml)
    {
        foreach ($array as $key => $value) {
            $this->keyValueToXml($key, $value, $xml);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param \Spryker\Zed\Ratepay\Business\Api\SimpleXMLElement $xml
     *
     * @return void
     */
    protected function keyValueToXml($key, $value, SimpleXMLElement &$xml)
    {
        if ($value !== null) {
            if ($value instanceof BuilderInterface) {
                if (is_numeric($key)) {
                    $key = $value->getRootTag();
                }
                $value = $value->buildData();
            }

            if (substr($key, 0, 1) === '@') {
                $attributeKey = substr($key, 1);
                $xml->addAttribute($attributeKey, $value);
                return;
            }

            if ($key === '#') {
                $xml->{0} = $value;
                return;
            }

            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->arrayToXml($value, $subnode);
                return;
            }

            $xml->addCDataChild($key, $value);
        }
    }

    /**
     * @return string
     */
    public function toXml()
    {
        $data = $this->buildData();
        $rootTag = $this->getRootTag();
        $xml = new SimpleXMLElement('<' . $rootTag . '></' . $rootTag . '>');
        $this->arrayToXml($data, $xml);

        return $xml->asXML();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toXml();
    }
}
