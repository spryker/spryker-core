<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException;
use Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

class PriceProductScheduleMapper implements PriceProductScheduleMapperInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected $priceProductScheduleConfig;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig $priceProductScheduleConfig
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        PriceProductScheduleConfig $priceProductScheduleConfig,
        PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade,
        PriceProductScheduleToProductFacadeInterface $productFacade,
        PriceProductScheduleToStoreFacadeInterface $storeFacade,
        PriceProductScheduleToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->priceProductScheduleConfig = $priceProductScheduleConfig;
        $this->priceProductFacade = $priceProductFacade;
        $this->productFacade = $productFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @throws \Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function mapPriceProductScheduleImportTransferToPriceProductScheduleTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductTransfer = $this->mapPriceProductScheduleImportTransferToPriceProductTransfer(
            $priceProductScheduleImportTransfer,
            new PriceProductTransfer()
        );

        return $priceProductScheduleTransfer
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true)
            ->setPriceProduct($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @throws \Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapPriceProductScheduleImportTransferToPriceProductTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $moneyValueTransfer = $this->mapPriceProductScheduleEntityToMoneyValueTransfer(
            $priceProductScheduleImportTransfer,
            new MoneyValueTransfer()
        );

        $priceTypeTransfer = $this->priceProductFacade->findPriceTypeByName(
            $priceProductScheduleImportTransfer->getPriceTypeName()
        );

        $priceProductDimensionTransfer = $this->getDefaultPriceProductDimension();

        $priceProductTransfer
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true)
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer)
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setMoneyValue($moneyValueTransfer)
            ->setPriceDimension($priceProductDimensionTransfer);

        if ($priceProductScheduleImportTransfer->getSkuProductAbstract()) {
            $productAbstractId = $this->productFacade->findProductAbstractIdBySku(
                $priceProductScheduleImportTransfer->getSkuProductAbstract()
            );
            if ($productAbstractId === null) {
                throw new PriceProductScheduleListImportException(
                    sprintf(
                        'Abstract product was not found by provided sku "%s"',
                        $priceProductScheduleImportTransfer->getSkuProductAbstract()
                    )
                );
            }
            $priceProductTransfer->setIdProductAbstract($productAbstractId);
            $priceProductTransfer->setSkuProductAbstract($priceProductScheduleImportTransfer->getSkuProductAbstract());
        }

        if ($priceProductScheduleImportTransfer->getSkuProduct()) {
            $productConcreteId = $this->productFacade->findProductConcreteIdBySku(
                $priceProductScheduleImportTransfer->getSkuProduct()
            );

            if ($productConcreteId === null) {
                throw new PriceProductScheduleListImportException(
                    sprintf(
                        'Concrete product was not found by provided sku "%s"',
                        $priceProductScheduleImportTransfer->getSkuProduct()
                    )
                );
            }

            $priceProductTransfer->setIdProduct($productConcreteId);
            $priceProductTransfer->setSkuProduct($priceProductScheduleImportTransfer->getSkuProduct());
        }

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @throws \Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapPriceProductScheduleEntityToMoneyValueTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        try {
            $currencyTransfer = $this->currencyFacade->fromIsoCode($priceProductScheduleImportTransfer->getCurrencyName());
        } catch (CurrencyNotFoundException $e) {
            throw new PriceProductScheduleListImportException($e);
        }

        $storeTransfer = $this->storeFacade->getStoreByName($priceProductScheduleImportTransfer->getStoreName());

        if ($storeTransfer === null) {
            throw new PriceProductScheduleListImportException(
                sprintf(
                    'Store was not found by provided name "%s"',
                    $priceProductScheduleImportTransfer->getStoreName()
                )
            );
        }

        return $moneyValueTransfer
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true)
            ->setNetAmount($priceProductScheduleImportTransfer->getNetAmount())
            ->setGrossAmount($priceProductScheduleImportTransfer->getGrossAmount())
            ->setCurrency($currencyTransfer)
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setFkStore($storeTransfer->getIdStore());
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function getDefaultPriceProductDimension(): PriceProductDimensionTransfer
    {
        return (new PriceProductDimensionTransfer())
            ->setType($this->priceProductScheduleConfig->getPriceDimensionDefault());
    }
}
