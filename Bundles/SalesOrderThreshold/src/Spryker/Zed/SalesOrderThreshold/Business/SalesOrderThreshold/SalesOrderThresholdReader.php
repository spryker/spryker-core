<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationReaderInterface;
use Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface;

class SalesOrderThresholdReader implements SalesOrderThresholdReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface
     */
    protected $salesOrderThresholdRepository;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationReaderInterface
     */
    protected $translationReader;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface $salesOrderThresholdRepository
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationReaderInterface $translationReader
     */
    public function __construct(
        SalesOrderThresholdRepositoryInterface $salesOrderThresholdRepository,
        SalesOrderThresholdTranslationReaderInterface $translationReader
    ) {
        $this->salesOrderThresholdRepository = $salesOrderThresholdRepository;
        $this->translationReader = $translationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer[]
     */
    public function getSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $salesOrderThresholdTransfers = $this->salesOrderThresholdRepository
            ->getSalesOrderThresholds(
                $storeTransfer,
                $currencyTransfer
            );

        foreach ($salesOrderThresholdTransfers as $salesOrderThresholdTransfer) {
            $this->translationReader->hydrateLocalizedMessages($salesOrderThresholdTransfer);
        }

        return $salesOrderThresholdTransfers;
    }
}
