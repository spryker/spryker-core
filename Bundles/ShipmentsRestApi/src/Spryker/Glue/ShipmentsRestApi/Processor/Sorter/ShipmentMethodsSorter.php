<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi\Processor\Sorter;

use Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface;

class ShipmentMethodsSorter implements ShipmentMethodsSorterInterface
{
    protected const GET_METHOD_PREFIX = 'get';

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer[] $restShipmentMethodAttributeTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer[]
     */
    public function sortShipmentMethodAttributesTransfers(
        array $restShipmentMethodAttributeTransfers,
        RestRequestInterface $restRequest
    ): array {
        $sorts = $restRequest->getSort();

        uasort(
            $restShipmentMethodAttributeTransfers,
            function (
                RestShipmentMethodAttributesTransfer $currentRestShipmentMethodAttributeTransfers,
                RestShipmentMethodAttributesTransfer $nextRestShipmentMethodAttributeTransfers
            ) use ($sorts) {
                $sortsCount = count($sorts);
                $firstSort = current($sorts);

                for ($currentIndex = 1; $currentIndex < $sortsCount; $currentIndex++) {
                    $currentSort = $sorts[$currentIndex];
                    $previousIndex = $currentIndex - 1;
                    $previousSort = $sorts[$previousIndex];

                    $isPreviousSortingEqual = $this->isPreviousSortingEqual(
                        $currentRestShipmentMethodAttributeTransfers,
                        $nextRestShipmentMethodAttributeTransfers,
                        $previousSort
                    );

                    if ($isPreviousSortingEqual) {
                        return $this->comparator(
                            $currentRestShipmentMethodAttributeTransfers,
                            $nextRestShipmentMethodAttributeTransfers,
                            $currentSort
                        );
                    }
                }

                return $this->comparator(
                    $currentRestShipmentMethodAttributeTransfers,
                    $nextRestShipmentMethodAttributeTransfers,
                    $firstSort
                );
            }
        );

        return $restShipmentMethodAttributeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer $currentRestShipmentMethodAttributeTransfer
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer $nextRestShipmentMethodAttributeTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface $sort
     *
     * @return bool
     */
    protected function isPreviousSortingEqual(
        RestShipmentMethodAttributesTransfer $currentRestShipmentMethodAttributeTransfer,
        RestShipmentMethodAttributesTransfer $nextRestShipmentMethodAttributeTransfer,
        SortInterface $sort
    ): bool {
        $sortingField = $sort->getField();

        $currentSortingValue = $this->getValueByPropertyName(
            $currentRestShipmentMethodAttributeTransfer,
            $sortingField
        );

        $nextSortingValue = $this->getValueByPropertyName(
            $nextRestShipmentMethodAttributeTransfer,
            $sortingField
        );

        return $currentSortingValue === $nextSortingValue;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer $restShipmentMethodAttributeTransfer
     * @param string $propertyName
     *
     * @return mixed
     */
    protected function getValueByPropertyName(
        RestShipmentMethodAttributesTransfer $restShipmentMethodAttributeTransfer,
        string $propertyName
    ) {
        $getterMethodName = $this->makeGetterMethodNameByPropertyName($propertyName);

        if (method_exists($restShipmentMethodAttributeTransfer, $getterMethodName)) {
            return $restShipmentMethodAttributeTransfer->$getterMethodName();
        }

        return null;
    }

    /**
     * @param string $propertyName
     *
     * @return string
     */
    protected function makeGetterMethodNameByPropertyName(string $propertyName): string
    {
        return sprintf('%s%s', static::GET_METHOD_PREFIX, ucfirst($propertyName));
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer $currentRestShipmentMethodAttributeTransfer
     * @param \Generated\Shared\Transfer\RestShipmentMethodAttributesTransfer $nextRestShipmentMethodAttributeTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface $sort
     *
     * @return int
     */
    protected function comparator(
        RestShipmentMethodAttributesTransfer $currentRestShipmentMethodAttributeTransfer,
        RestShipmentMethodAttributesTransfer $nextRestShipmentMethodAttributeTransfer,
        SortInterface $sort
    ): int {
        $sortingField = $sort->getField();
        $sortDirection = $sort->getDirection();

        $currentSortingValue = $this->getValueByPropertyName(
            $currentRestShipmentMethodAttributeTransfer,
            $sortingField
        );

        $nextSortingValue = $this->getValueByPropertyName(
            $nextRestShipmentMethodAttributeTransfer,
            $sortingField
        );

        if ($sortDirection === SortInterface::SORT_DESC) {
            return $currentSortingValue < $nextSortingValue ? 1 : -1;
        }

        return $currentSortingValue < $nextSortingValue ? -1 : 1;
    }
}
