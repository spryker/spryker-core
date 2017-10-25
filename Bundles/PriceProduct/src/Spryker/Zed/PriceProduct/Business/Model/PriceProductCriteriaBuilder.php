<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreInterface;

class PriceProductCriteriaBuilder implements PriceProductCriteriaBuilderInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface
     */
    protected $priceProductTypeReader;

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreInterface $storeFacade
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceProductTypeReaderInterface $priceProductTypeReader
     */
    public function __construct(
        PriceProductToCurrencyInterface $currencyFacade,
        PriceProductToPriceInterface $priceFacade,
        PriceProductToStoreInterface $storeFacade,
        PriceProductTypeReaderInterface $priceProductTypeReader
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->priceFacade = $priceFacade;
        $this->storeFacade = $storeFacade;
        $this->priceProductTypeReader = $priceProductTypeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaFromFilter(PriceProductFilterTransfer $priceProductFilterTransfer)
    {
        return (new PriceProductCriteriaTransfer())
            ->setIdCurrency(
                $this->getCurrencyFromFilter($priceProductFilterTransfer)->getIdCurrency()
            )->setIdStore(
                $this->getStoreFromFilter($priceProductFilterTransfer)->getIdStore()
            )->setPriceMode(
                $this->getPriceModeFromFilter($priceProductFilterTransfer)
            )->setPriceType(
                $this->priceProductTypeReader->handleDefaultPriceType($priceProductFilterTransfer->getPriceTypeName())
            );
    }

    /**
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaWithDefaultValues($priceTypeName = null)
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
            $this->priceProductTypeReader->handleDefaultPriceType($priceTypeName)
        );
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
            $priceMode = $this->priceFacade->getDefaultPriceMode();
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
