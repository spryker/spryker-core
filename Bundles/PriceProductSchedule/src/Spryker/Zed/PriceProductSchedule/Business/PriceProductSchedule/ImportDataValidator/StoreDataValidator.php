<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\ImportDataValidator;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface;

class StoreDataValidator extends AbstractImportDataValidator
{
    protected const ERROR_MESSAGE_STORE_NOT_FOUND = 'Store was not found by provided name %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface
     */
    protected $priceProductScheduleStoreFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface $priceProductScheduleStoreFinder
     */
    public function __construct(StoreFinderInterface $priceProductScheduleStoreFinder)
    {
        $this->priceProductScheduleStoreFinder = $priceProductScheduleStoreFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer|null
     */
    public function validatePriceProductScheduleImportTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): ?PriceProductScheduleListImportErrorTransfer {
        if ($this->isStoreValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                sprintf(
                    static::ERROR_MESSAGE_STORE_NOT_FOUND,
                    $priceProductScheduleImportTransfer->getStoreName()
                )
            );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isStoreValid(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        if ($priceProductScheduleImportTransfer->getStoreName() === null) {
            return false;
        }

        $storeTransfer = $this->priceProductScheduleStoreFinder
            ->findStoreByName($priceProductScheduleImportTransfer->getStoreName());

        if ($storeTransfer === null) {
            return false;
        }

        return true;
    }
}
