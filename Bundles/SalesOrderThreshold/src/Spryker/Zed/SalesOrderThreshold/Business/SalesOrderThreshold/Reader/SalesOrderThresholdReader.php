<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\Reader;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\SalesOrderThresholdReaderInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator\SalesOrderThresholdTranslationHydratorInterface;
use Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface;

class SalesOrderThresholdReader implements SalesOrderThresholdReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface
     */
    protected $salesOrderThresholdRepository;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator\SalesOrderThresholdTranslationHydratorInterface
     */
    protected $translationHydrator;

    /**
     * @var array<array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer>>
     */
    protected static $salesOrderThresholdTransfersCache = [];

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface $salesOrderThresholdRepository
     * @param \Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator\SalesOrderThresholdTranslationHydratorInterface $translationHydrator
     */
    public function __construct(
        SalesOrderThresholdRepositoryInterface $salesOrderThresholdRepository,
        SalesOrderThresholdTranslationHydratorInterface $translationHydrator
    ) {
        $this->salesOrderThresholdRepository = $salesOrderThresholdRepository;
        $this->translationHydrator = $translationHydrator;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer>
     */
    public function getSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $currencyTransferAndStoreTransferCacheKey = $this->generateSalesOrderThresholdTransfersCacheKey($currencyTransfer, $storeTransfer);

        if ($this->hasSalesOrderThresholdTransfersByCacheKey($currencyTransferAndStoreTransferCacheKey)) {
            return $this->getSalesOrderThresholdTransfersByCacheKey($currencyTransferAndStoreTransferCacheKey);
        }

        $salesOrderThresholdTransfers = $this->salesOrderThresholdRepository
            ->getSalesOrderThresholds(
                $storeTransfer,
                $currencyTransfer,
            );

        $salesOrderThresholdTransfers = $this->translationHydrator->expandWithLocalizedMessagesCollection($salesOrderThresholdTransfers);
        $this->cacheSalesOrderThresholdTransfersByCacheKey($salesOrderThresholdTransfers, $currencyTransferAndStoreTransferCacheKey);

        return $salesOrderThresholdTransfers;
    }

    /**
     * @param string $currencyTransferAndStoreTransferCacheKey
     *
     * @return bool
     */
    protected function hasSalesOrderThresholdTransfersByCacheKey(string $currencyTransferAndStoreTransferCacheKey): bool
    {
        return isset(static::$salesOrderThresholdTransfersCache[$currencyTransferAndStoreTransferCacheKey]);
    }

    /**
     * @param string $currencyTransferAndStoreTransferCacheKey
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer>
     */
    protected function getSalesOrderThresholdTransfersByCacheKey(string $currencyTransferAndStoreTransferCacheKey): array
    {
        return static::$salesOrderThresholdTransfersCache[$currencyTransferAndStoreTransferCacheKey];
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function generateSalesOrderThresholdTransfersCacheKey(CurrencyTransfer $currencyTransfer, StoreTransfer $storeTransfer): string
    {
        return sprintf(
            '`%s`|`%s`',
            ($currencyTransfer->getIdCurrency() ?? $currencyTransfer->getCode()),
            ($storeTransfer->getIdStore() ?? $storeTransfer->getName()),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderThresholdTransfer> $salesOrderThresholdTransfers
     * @param string $currencyTransferAndStoreTransferCacheKey
     *
     * @return void
     */
    protected function cacheSalesOrderThresholdTransfersByCacheKey(
        array $salesOrderThresholdTransfers,
        string $currencyTransferAndStoreTransferCacheKey
    ): void {
        static::$salesOrderThresholdTransfersCache[$currencyTransferAndStoreTransferCacheKey] = $salesOrderThresholdTransfers;
    }
}
