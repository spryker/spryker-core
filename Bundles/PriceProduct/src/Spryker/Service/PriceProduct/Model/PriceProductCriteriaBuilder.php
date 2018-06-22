<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;
use Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface;
use Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Service\PriceProduct\PriceProductConfig;
use Spryker\Shared\PriceProduct\PriceProductConstants;

class PriceProductCriteriaBuilder implements PriceProductCriteriaBuilderInterface
{
    /**
     * @var \Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Service\PriceProduct\PriceProductConfig
     */
    protected $priceProductConfig;

    /**
     * @param \Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeInterface $priceFacade
     * @param \Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface $storeFacade
     * @param \Spryker\Service\PriceProduct\PriceProductConfig $priceProductConfig
     */
    public function __construct(
        PriceProductToCurrencyFacadeInterface $currencyFacade,
        PriceProductToPriceFacadeInterface $priceFacade,
        PriceProductToStoreFacadeInterface $storeFacade,
        PriceProductConfig $priceProductConfig
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->priceFacade = $priceFacade;
        $this->storeFacade = $storeFacade;
        $this->priceProductConfig = $priceProductConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaFromFilter(PriceProductFilterTransfer $priceProductFilterTransfer): PriceProductCriteriaTransfer
    {
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->fromArray($priceProductFilterTransfer->toArray(), true);

        return $priceProductCriteriaTransfer
            ->setIdCurrency(
                $this->getCurrencyFromFilter($priceProductFilterTransfer)->getIdCurrency()
            )->setIdStore(
                $this->getStoreFromFilter($priceProductFilterTransfer)->getIdStore()
            )->setPriceMode(
                $this->getPriceModeFromFilter($priceProductFilterTransfer)
            )->setPriceType(
                $priceProductFilterTransfer->getPriceTypeName() ?: $this->priceProductConfig->getPriceTypeDefaultName()
            );
    }

    /**
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaWithDefaultValues($priceTypeName = null): PriceProductCriteriaTransfer
    {
        return (new PriceProductCriteriaTransfer())
        ->setPriceMode(
            $this->priceFacade->getDefaultPriceMode()
        )
        ->setIdCurrency(
            $this->currencyFacade->getDefaultCurrencyForCurrentStore()->getIdCurrency()
        )
        ->setIdStore(
            $this->storeFacade->getCurrentStore()->getIdStore()
        )
        ->setPriceType(
            $priceTypeName ?: $this->priceProductConfig->getPriceTypeDefaultName()
        )
        ->setPriceDimension(
            (new PriceProductDimensionTransfer())->setType(PriceProductConstants::PRICE_DIMENSION_DEFAULT)
        );
    }

    /**
     * @param string|null $priceDimensionType
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaWithPriceDimension(?string $priceDimensionType = null): PriceProductCriteriaTransfer
    {
        if (!$priceDimensionType) {
            $priceDimensionType = PriceProductConstants::PRICE_DIMENSION_DEFAULT;
        }

        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setType($priceDimensionType);

        return (new PriceProductCriteriaTransfer())
            ->setPriceDimension($priceProductDimensionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return string
     */
    protected function getPriceModeFromFilter(PriceProductFilterTransfer $priceFilterTransfer)
    {
        $priceMode = $priceFilterTransfer->getPriceMode();
        if (!$priceMode) {
            return $this->priceFacade->getDefaultPriceMode();
        }
        return $priceMode;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyFromFilter(PriceProductFilterTransfer $priceFilterTransfer)
    {
        if ($priceFilterTransfer->getCurrencyIsoCode()) {
            return $this->currencyFacade->fromIsoCode($priceFilterTransfer->getCurrencyIsoCode());
        }

        return $this->currencyFacade->getDefaultCurrencyForCurrentStore();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreFromFilter(PriceProductFilterTransfer $priceFilterTransfer)
    {
        if ($priceFilterTransfer->getStoreName()) {
            return $this->storeFacade->getStoreByName($priceFilterTransfer->getStoreName());
        }

        return $this->storeFacade->getCurrentStore();
    }
}
