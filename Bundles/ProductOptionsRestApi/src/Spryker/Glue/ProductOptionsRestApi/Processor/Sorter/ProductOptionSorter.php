<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Sorter;

use Generated\Shared\Transfer\RestProductOptionsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface;
use Spryker\Glue\ProductOptionsRestApi\ProductOptionsRestApiConfig;

class ProductOptionSorter implements ProductOptionSorterInterface
{
    protected const SORT_VALUE_DELIMITER = '.';

    /**
     * @param \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer[] $restProductOptionsAttributesTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer[]
     */
    public function sortRestProductOptionsAttributesTransfers(
        array $restProductOptionsAttributesTransfers,
        array $sorts
    ): array {
        $sorts = array_values($this->filterSorts($sorts));

        if (!$sorts) {
            return $restProductOptionsAttributesTransfers;
        }

        usort($restProductOptionsAttributesTransfers, function (
            RestProductOptionsAttributesTransfer $currentRestProductOptionsAttributesTransfer,
            RestProductOptionsAttributesTransfer $nextRestProductOptionsAttributesTransfer
        ) use ($sorts) {
            return $this->compare(
                $currentRestProductOptionsAttributesTransfer,
                $nextRestProductOptionsAttributesTransfer,
                $sorts
            );
        });

        return $restProductOptionsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer $currentRestProductOptionsAttributesTransfer
     * @param \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer $nextRestProductOptionsAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     * @param int $index
     *
     * @return int
     */
    protected function compare(
        RestProductOptionsAttributesTransfer $currentRestProductOptionsAttributesTransfer,
        RestProductOptionsAttributesTransfer $nextRestProductOptionsAttributesTransfer,
        array $sorts,
        int $index = 0
    ): int {
        $currentSort = $sorts[$index];
        $field = explode(static::SORT_VALUE_DELIMITER, $currentSort->getField())[1];
        $currentPropertyValue = $nextPropertyValue = null;

        if ($currentRestProductOptionsAttributesTransfer->offsetExists($field)) {
            $currentPropertyValue = $currentRestProductOptionsAttributesTransfer->offsetGet($field);
            $nextPropertyValue = $nextRestProductOptionsAttributesTransfer->offsetGet($field);
        }

        if ($currentPropertyValue === $nextPropertyValue) {
            if (!isset($sorts[$index + 1])) {
                return 0;
            }

            return $this->compare(
                $currentRestProductOptionsAttributesTransfer,
                $nextRestProductOptionsAttributesTransfer,
                $sorts,
                $index + 1
            );
        }

        if ($currentSort->getDirection() === SortInterface::SORT_DESC) {
            return $currentPropertyValue < $nextPropertyValue ? 1 : -1;
        }

        return $currentPropertyValue < $nextPropertyValue ? -1 : 1;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     */
    protected function filterSorts(array $sorts): array
    {
        return array_filter($sorts, function (SortInterface $sort) {
            return explode(static::SORT_VALUE_DELIMITER, $sort->getField())[0] === ProductOptionsRestApiConfig::RESOURCE_PRODUCT_OPTIONS;
        });
    }
}
