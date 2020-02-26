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
        $sorts = array_values($this->filterSorts($restRequest->getSort()));

        if (!$sorts) {
            return $restShipmentMethodAttributeTransfers;
        }
        $sortsCount = count($sorts) - 1;
        $lastSort = $sorts[$sortsCount];
        uasort(
            $restShipmentMethodAttributeTransfers,
            function (
                RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfer,
                RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfer
            ) use (
                $sorts,
                $sortsCount,
                $lastSort
            ) {
                for ($sortIndex = 0; $sortIndex < $sortsCount; $sortIndex++) {
                    $currentSortedPropertyValue = $this->getPropertyValue($currentRestShipmentMethodAttributeTransfer, $sorts[$sortIndex]);
                    $nextSortedPropertyValue = $this->getPropertyValue($nextRestShipmentMethodAttributeTransfer, $sorts[$sortIndex]);

                    if ($currentSortedPropertyValue != $nextSortedPropertyValue) {
                        return $this->compareValues($currentSortedPropertyValue, $nextSortedPropertyValue, $sorts[$sortIndex]);
                    }
                }
                $currentSortedPropertyValue = $this->getPropertyValue($currentRestShipmentMethodAttributeTransfer, $lastSort);
                $nextSortedPropertyValue = $this->getPropertyValue($nextRestShipmentMethodAttributeTransfer, $lastSort);

                return $this->compareValues($currentSortedPropertyValue, $nextSortedPropertyValue, $lastSort);
            }
        );

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
    protected function getPropertyValue(RestShipmentMethodsAttributesTransfer $restShipmentMethodAttributeTransfer, SortInterface $currentSort): ?string
    {
        $field = explode(static::SORT_VALUE_DELIMITER, $currentSort->getField())[1];

        return $restShipmentMethodAttributeTransfer->offsetExists($field)
            ? (string)$restShipmentMethodAttributeTransfer->offsetGet($field) : null;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[]
     */
    protected function filterSorts(array $sorts): array
    {
        return array_filter($sorts, function (SortInterface $sort) {
            return explode(static::SORT_VALUE_DELIMITER, $sort->getField())[0] === ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS;
        });
    }
}
