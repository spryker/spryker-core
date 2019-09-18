<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Sorter;

use Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface;

class ShipmentMethodSorter implements ShipmentMethodSorterInterface
{
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
        $sorts = $restRequest->getSort();

        if (!$sorts) {
            return $restShipmentMethodAttributeTransfers;
        }

        uasort(
            $restShipmentMethodAttributeTransfers,
            function (
                RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfers,
                RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfers
            ) use ($sorts) {
                return $this->compare(
                    $currentRestShipmentMethodAttributeTransfers,
                    $nextRestShipmentMethodAttributeTransfers,
                    $sorts
                );
            }
        );

        return $restShipmentMethodAttributeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfer
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     * @param int $index
     *
     * @return int
     */
    protected function compare(
        RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfer,
        RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfer,
        array $sorts,
        int $index = 0
    ) {
        $currentSort = $sorts[$index];
        $currentSortedPropertyValue = $currentRestShipmentMethodAttributeTransfer->offsetExists($currentSort->getField())
            ? $currentRestShipmentMethodAttributeTransfer->offsetGet($currentSort->getField()) : null;
        $nextSortedPropertyValue = $nextRestShipmentMethodAttributeTransfer->offsetExists($currentSort->getField())
            ? $nextRestShipmentMethodAttributeTransfer->offsetGet($currentSort->getField()) : null;

        if ($currentSortedPropertyValue === $nextSortedPropertyValue) {
            if (!isset($sorts[$index + 1])) {
                return 0;
            }

            return $this->compare(
                $currentRestShipmentMethodAttributeTransfer,
                $nextRestShipmentMethodAttributeTransfer,
                $sorts,
                $index + 1
            );
        }

        if ($currentSort->getDirection() === SortInterface::SORT_DESC) {
            return $currentSortedPropertyValue < $nextSortedPropertyValue ? 1 : -1;
        }

        return $currentSortedPropertyValue < $nextSortedPropertyValue ? -1 : 1;
    }
}
