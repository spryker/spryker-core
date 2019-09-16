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
                return $this->sortRestShipmentMethodAttributeTransfers(
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface $sort
     *
     * @return bool
     */
    protected function isPreviousSortingEqual(
        RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfer,
        RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfer,
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
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer $restShipmentMethodAttributeTransfer
     * @param string $propertyName
     *
     * @return mixed
     */
    protected function getValueByPropertyName(
        RestShipmentMethodsAttributesTransfer $restShipmentMethodAttributeTransfer,
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
        return sprintf('get%s', ucfirst($propertyName));
    }

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfer
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface $sort
     *
     * @return int
     */
    protected function comparator(
        RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfer,
        RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfer,
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

    /**
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfers
     * @param \Generated\Shared\Transfer\RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfers
     * @param array $sorts
     *
     * @return int
     */
    protected function sortRestShipmentMethodAttributeTransfers(
        RestShipmentMethodsAttributesTransfer $currentRestShipmentMethodAttributeTransfers,
        RestShipmentMethodsAttributesTransfer $nextRestShipmentMethodAttributeTransfers,
        array $sorts
    ): int {
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
}
