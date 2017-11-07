<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProduct\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class PriceProductDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param null $currencyIsoCode
     * @param array $moneyValueOverwrite
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function haveProductPrice(
        ProductConcreteTransfer $productConcreteTransfer,
        $currencyIsoCode = null,
        array $moneyValueOverwrite
    )
    {
        $priceTypeTransfer = new PriceTypeTransfer();

        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setSkuProductAbstract($productConcreteTransfer->getAbstractSku())
            ->setSkuProduct($productConcreteTransfer->getSku())
            ->setPriceTypeName($this->getPriceProductFacade()->getDefaultPriceTypeName())
            ->setPriceType($priceTypeTransfer);

        if (!$currencyIsoCode) {
            $currencyIsoCode = $this->getStoreFacade()->getCurrentStore()->getDefaultCurrencyIsoCode();
        }

        $currencyTransfer = $this->getCurrencyFacade()->fromIsoCode($currencyIsoCode);
        $storeTransfer = $this->getStoreFacade()->getCurrentStore();

        $moneyValueTransfer = (new MoneyValueBuilder($moneyValueOverwrite))->build()
            ->setCurrency($currencyTransfer)
            ->setFkStore($storeTransfer->getIdStore())
            ->setFkCurrency($currencyTransfer->getIdCurrency());

        $priceProductTransfer->setMoneyValue($moneyValueTransfer);

        return $this->getPriceProductFacade()->createPriceForProduct($priceProductTransfer);
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getLocator()->currency()->facade();
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return $this->getLocator()->priceProduct()->facade();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getLocator()->store()->facade();
    }
}
