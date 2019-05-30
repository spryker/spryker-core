<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use DateTime;
use Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceType\PriceTypeFinderInterface;
use Spryker\Zed\PriceProductSchedule\Business\Product\ProductFinderInterface;
use Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;
use Throwable;

class PriceProductScheduleValidator implements PriceProductScheduleValidatorInterface
{
    protected const ERROR_MESSAGE_GROSS_AND_NET_VALUE = 'Gross and Net Amount must be integer.';
    protected const ERROR_MESSAGE_ACTIVE_FROM_AND_ACTIVE_TO = 'Dates must be in right format and to date must be greater than from.';
    protected const ERROR_MESSAGE_SCHEDULED_PRICE_ALREADY_EXISTS = 'Scheduled price already exists.';

    protected const ERROR_MESSAGE_CURRENCY_NOT_FOUND = 'Currency was not found by provided iso code %s';
    protected const ERROR_MESSAGE_STORE_NOT_FOUND = 'Store was not found by provided name %s';

    protected const ERROR_MESSAGE_PRICE_TYPE_NOT_FOUND = 'Price type was not found by provided sku %s';

    protected const ERROR_MESSAGE_PRODUCT_CONCRETE_NOT_FOUND = 'Concrete product was not found by provided sku %s';
    protected const ERROR_MESSAGE_PRODUCT_ABSTRACT_NOT_FOUND = 'Abstract product was not found by provided sku %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleImportMapperInterface
     */
    protected $priceProductScheduleImportMapper;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface
     */
    protected $priceProductScheduleStoreFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface
     */
    protected $priceProductScheduleCurrencyFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceType\PriceTypeFinderInterface
     */
    protected $priceTypeFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Product\ProductFinderInterface
     */
    protected $productFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleImportMapperInterface $priceProductScheduleImportMapper
     * @param \Spryker\Zed\PriceProductSchedule\Business\Store\StoreFinderInterface $priceProductScheduleStoreFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface $priceProductScheduleCurrencyFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceType\PriceTypeFinderInterface $priceTypeFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\Product\ProductFinderInterface $productFinder
     */
    public function __construct(
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductScheduleImportMapperInterface $priceProductScheduleImportMapper,
        StoreFinderInterface $priceProductScheduleStoreFinder,
        CurrencyFinderInterface $priceProductScheduleCurrencyFinder,
        PriceTypeFinderInterface $priceTypeFinder,
        ProductFinderInterface $productFinder
    ) {
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
        $this->priceProductScheduleImportMapper = $priceProductScheduleImportMapper;
        $this->priceProductScheduleStoreFinder = $priceProductScheduleStoreFinder;
        $this->priceProductScheduleCurrencyFinder = $priceProductScheduleCurrencyFinder;
        $this->priceTypeFinder = $priceTypeFinder;
        $this->productFinder = $productFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer|null
     */
    public function validatePriceProductScheduleImportTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): ?PriceProductScheduleListImportErrorTransfer {
        if ($this->isPricesValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                static::ERROR_MESSAGE_GROSS_AND_NET_VALUE
            );
        }

        if ($this->isDatesValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                static::ERROR_MESSAGE_ACTIVE_FROM_AND_ACTIVE_TO
            );
        }

        if ($this->isCurrencyValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                sprintf(
                    static::ERROR_MESSAGE_CURRENCY_NOT_FOUND,
                    $priceProductScheduleImportTransfer->getCurrencyCode()
                )
            );
        }

        if ($this->isStoreValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                sprintf(
                    static::ERROR_MESSAGE_STORE_NOT_FOUND,
                    $priceProductScheduleImportTransfer->getStoreName()
                )
            );
        }

        if ($this->isProductAbstractDataValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                sprintf(
                    static::ERROR_MESSAGE_PRODUCT_ABSTRACT_NOT_FOUND,
                    $priceProductScheduleImportTransfer->getSkuProductAbstract()
                )
            );
        }

        if ($this->isProductConcreteDataValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                sprintf(
                    static::ERROR_MESSAGE_PRODUCT_CONCRETE_NOT_FOUND,
                    $priceProductScheduleImportTransfer->getSkuProduct()
                )
            );
        }

        $priceProductScheduleCriteriaFilterTransfer = $this->priceProductScheduleImportMapper
            ->mapPriceProductScheduleImportTransferToPriceProductScheduleCriteriaFilterTransfer(
                $priceProductScheduleImportTransfer,
                new PriceProductScheduleCriteriaFilterTransfer()
            );

        $priceProductScheduleTransferCount = $this->priceProductScheduleRepository->findCountPriceProductScheduleByCriteriaFilter(
            $priceProductScheduleCriteriaFilterTransfer
        );

        if ($priceProductScheduleTransferCount > 0) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                static::ERROR_MESSAGE_SCHEDULED_PRICE_ALREADY_EXISTS
            );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isPricesValid(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        return is_numeric($priceProductScheduleImportTransfer->getGrossAmount())
            && is_numeric($priceProductScheduleImportTransfer->getNetAmount())
            && !is_float($priceProductScheduleImportTransfer->getGrossAmount())
            && !is_float($priceProductScheduleImportTransfer->getNetAmount());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isDatesValid(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        try {
            $activeFrom = new DateTime($priceProductScheduleImportTransfer->getActiveFrom());
            $activeTo = new DateTime($priceProductScheduleImportTransfer->getActiveTo());

            return $activeTo > $activeFrom;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isCurrencyValid(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        if ($priceProductScheduleImportTransfer->getCurrencyCode() === null) {
            return false;
        }

        $currencyTransfer = $this->priceProductScheduleCurrencyFinder
            ->findCurrencyByIsoCode($priceProductScheduleImportTransfer->getCurrencyCode());

        if ($currencyTransfer === null) {
            return false;
        }

        return true;
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

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isProductAbstractDataValid(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): bool {
        if ($priceProductScheduleImportTransfer->getSkuProductAbstract() === null
            && $priceProductScheduleImportTransfer->getSkuProduct() === null) {
            return false;
        }

        if ($priceProductScheduleImportTransfer->getSkuProductAbstract()) {
            $productAbstractId = $this->productFinder
                ->findProductAbstractIdBySku($priceProductScheduleImportTransfer->getSkuProductAbstract());

            if ($productAbstractId === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isProductConcreteDataValid(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): bool {
        if ($priceProductScheduleImportTransfer->getSkuProductAbstract() === null
            && $priceProductScheduleImportTransfer->getSkuProduct() === null) {
            return false;
        }

        if ($priceProductScheduleImportTransfer->getSkuProduct()) {
            $productConcreteId = $this->productFinder
                ->findProductConcreteIdBySku($priceProductScheduleImportTransfer->getSkuProduct());

            if ($productConcreteId === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer
     */
    protected function createPriceProductScheduleListImportErrorTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        string $errorMessage
    ): PriceProductScheduleListImportErrorTransfer {
        return (new PriceProductScheduleListImportErrorTransfer())
            ->setPriceProductScheduleImport($priceProductScheduleImportTransfer)
            ->setMessage($errorMessage);
    }
}
