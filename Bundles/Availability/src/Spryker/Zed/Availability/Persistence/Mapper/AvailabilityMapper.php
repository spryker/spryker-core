<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Spryker\DecimalObject\Decimal;

class AvailabilityMapper implements AvailabilityMapperInterface
{
    /**
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availabilityEntity
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function mapAvailabilityEntityToProductConcreteAvailabilityTransfer(
        SpyAvailability $availabilityEntity,
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): ProductConcreteAvailabilityTransfer {
        return $productConcreteAvailabilityTransfer
            ->setSku($availabilityEntity->getSku())
            ->setAvailability($availabilityEntity->getQuantity())
            ->setIsNeverOutOfStock($availabilityEntity->getIsNeverOutOfStock());
    }

    /**
     * @param array $availabilityAbstractEntityArray
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function mapAvailabilityEntityToProductAbstractAvailabilityTransfer(
        array $availabilityAbstractEntityArray,
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
    ): ProductAbstractAvailabilityTransfer {
        $availabilityAbstractEntityArray = $this->processAvailabilityAbstractEntityArray($availabilityAbstractEntityArray);

        return $productAbstractAvailabilityTransfer
            ->fromArray($availabilityAbstractEntityArray, true);
    }

    /**
     * @param array $availabilityAbstractData
     *
     * @return array
     */
    protected function processAvailabilityAbstractEntityArray(array $availabilityAbstractData): array
    {
        if (isset($availabilityAbstractData[ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY])) {
            $availabilityAbstractData[ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY] = $this->calculateReservation(
                $availabilityAbstractData[ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY]
            );
        }

        if (isset($availabilityAbstractData[ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK])) {
            $availabilityAbstractData[ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK] = $this->isNeverOutOfStock($availabilityAbstractData[ProductAbstractAvailabilityTransfer::IS_NEVER_OUT_OF_STOCK]);
        }

        if (isset($availabilityAbstractData[ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY]) &&
            isset($availabilityAbstractData[ProductAbstractAvailabilityTransfer::STOCK_QUANTITY])) {
            $availabilityAbstractData[ProductAbstractAvailabilityTransfer::AVAILABILITY] = (new Decimal($availabilityAbstractData[ProductAbstractAvailabilityTransfer::STOCK_QUANTITY]))
                ->subtract($availabilityAbstractData[ProductAbstractAvailabilityTransfer::RESERVATION_QUANTITY]);
        }

        return $availabilityAbstractData;
    }

    /**
     * @param string $reservationAggregationSet
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateReservation(string $reservationAggregationSet): Decimal
    {
        $reservation = new Decimal(0);
        $reservationItems = array_unique(explode(',', $reservationAggregationSet));
        foreach ($reservationItems as $item) {
            $itemParts = array_filter(explode(':', $item));
            if (!isset($itemParts[0])) {
                continue;
            }

            $reservation = $reservation->add(new Decimal($itemParts[1] ?? 0));
        }

        return $reservation;
    }

    /**
     * @param string $neverOutOfStockSet
     *
     * @return bool
     */
    protected function isNeverOutOfStock(string $neverOutOfStockSet): bool
    {
        return stripos($neverOutOfStockSet, 'true') !== false;
    }
}
