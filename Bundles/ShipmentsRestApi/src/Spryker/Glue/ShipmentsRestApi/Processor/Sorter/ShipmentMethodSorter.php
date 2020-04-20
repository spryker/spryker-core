<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Sorter;

use Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

class ShipmentMethodSorter implements ShipmentMethodSorterInterface
{
    protected const SORT_VALUE_DELIMITER = '.';

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[] $restShipmentMethodAttributeTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer[]
     */
    public function sortRestShipmentMethodsAttributesTransfers(
        array $restShipmentMethodAttributeTransfers,
        RestRequestInterface $restRequest
    ): array {
        $resourceSorts = array_values($this->filterSortsByResource($restRequest->getSort(), ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS));

        if (!$resourceSorts) {
            return $restShipmentMethodAttributeTransfers;
        }

        $callback = function (
            RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfer,
            RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfer
        ) use (
            $resourceSorts
        ) {
            foreach ($resourceSorts as $resourceSort) {
                $currentSortedPropertyValue = $this->getPropertyValueBySort($currentRestShipmentMethodAttributeTransfer, $resourceSort);
                $nextSortedPropertyValue = $this->getPropertyValueBySort($nextRestShipmentMethodAttributeTransfer, $resourceSort);

                if ($currentSortedPropertyValue != $nextSortedPropertyValue) {
                    return $this->compareValues($currentSortedPropertyValue, $nextSortedPropertyValue, $resourceSort);
                }
            }
        };

        uasort($restShipmentMethodAttributeTransfers, $callback);

        return $restShipmentMethodAttributeTransfers;
    }

    /**
     * @param string|null $currentSortedPropertyValue
     * @param string|null $nextSortedPropertyValue
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface $currentSort
     *
     * @return int
     */
    protected function compareValues(?string $currentSortedPropertyValue, ?string $nextSortedPropertyValue, SortInterface $currentSort): int
    {
        $isDescending = $currentSort->getDirection() === SortInterface::SORT_DESC;
        $isCurrentValueLessThanNextValue = $currentSortedPropertyValue < $nextSortedPropertyValue;

        return $isCurrentValueLessThanNextValue === $isDescending ? 1 : -1;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer $restShipmentMethodAttributeTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface $currentSort
     *
     * @return string|null
     */
    protected function getPropertyValueBySort(RestShipmentMethodsAttributesTransfer $restShipmentMethodAttributeTransfer, SortInterface $currentSort): ?string
    {
        $resourceValue = $this->getSortResourceValue($currentSort);

        return $restShipmentMethodAttributeTransfer->offsetExists($resourceValue)
            ? (string)$restShipmentMethodAttributeTransfer->offsetGet($resourceValue) : null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     * @param string $resource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[]
     */
    protected function filterSortsByResource(array $sorts, string $resource): array
    {
        return array_filter($sorts, function (SortInterface $sort) use ($resource) {
            return $this->getSortResourceName($sort) === $resource;
        });
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface $sort
     *
     * @return string|null
     */
    protected function getSortResourceName(SortInterface $sort): ?string
    {
        return explode(static::SORT_VALUE_DELIMITER, $sort->getField())[0] ?? null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface $sort
     *
     * @return string|null
     */
    protected function getSortResourceValue(SortInterface $sort): ?string
    {
        return explode(static::SORT_VALUE_DELIMITER, $sort->getField())[1] ?? null;
    }
}
