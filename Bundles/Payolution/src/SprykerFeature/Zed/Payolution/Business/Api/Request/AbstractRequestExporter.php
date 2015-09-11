<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request;


abstract class AbstractRequestExporter
{

    /**
     * @return array
     */
    public function toArray()
    {
        $requestData = [];
        $segmentName = $this->extractSegmentNameFromClassName();

        foreach ($this as $propertyName => $propertyValue) {
            if ($propertyValue instanceof self) {
                $requestData = array_merge($requestData, $propertyValue->toArray());
                continue;
            }

            if ($propertyValue === null) {
                continue;
            }

            $dataKey = sprintf('%s.%s', $segmentName, strtoupper($propertyName));
            $requestData[$dataKey] = $propertyValue;
        }

        return $requestData;
    }

    /**
     * @return string
     */
    private function extractSegmentNameFromClassName()
    {
        $reflection = new \ReflectionClass($this);

        return strtoupper($reflection->getShortName());
    }

    /**
     * @param \SimpleXMLElement|null $rootElement
     *
     * @return \SimpleXMLElement
     */
    public function toXml(\SimpleXMLElement $rootElement = null)
    {
        if ($rootElement === null) {
            $rootElement = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Request version="1.0"></Request>');
        }

        foreach ($this as $propertyName => $propertyValue) {
            if ($propertyValue instanceof self) {
                $propertyElement = $rootElement->addChild(ucfirst($propertyName));
                $propertyValue->toXml($propertyElement);
                continue;
            }

            if ($propertyValue === null) {
                continue;
            }

            if ($this->isXmlAttributeProperty($propertyName)) {
                $rootElement->addAttribute($propertyName, $propertyValue);
                continue;
            }

            $rootElement->addChild(ucfirst($propertyName), $propertyValue);
        }

        return $rootElement;
    }

    /**
     * @param $propertyName
     *
     * @return bool
     */
    private function isXmlAttributeProperty($propertyName)
    {
        return in_array($propertyName, $this->getXmlAttributeProperties());
    }

    /**
     * Returns a list of class members that are rendered as XML element attributes
     * instead of element values.
     *
     * @return array
     */
    protected function getXmlAttributeProperties()
    {
        return [];
    }

}
