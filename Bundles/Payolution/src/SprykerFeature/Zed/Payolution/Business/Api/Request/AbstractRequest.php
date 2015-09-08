<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request;

abstract class AbstractRequest
{
    /**
     * @return array
     */
    public function toArray()
    {
        $requestData = [];
        $segmentName = $this->extractSegmentNameFromClassName();

        foreach ($this as $propertyName => $propertyValue) {
            if ($propertyValue instanceof AbstractRequest) {
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
}
