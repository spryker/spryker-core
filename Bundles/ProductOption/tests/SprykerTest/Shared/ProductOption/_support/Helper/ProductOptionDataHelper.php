<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductOption\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\ProductOptionGroupBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductOptionDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $productOptionGroupOverride
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function haveProductOptionGroup(array $productOptionGroupOverride = []): ProductOptionGroupTransfer
    {
        $productOptionGroupTransfer = (new ProductOptionGroupBuilder($productOptionGroupOverride))
            ->withProductOptionValue()
            ->withAnotherProductOptionValue()
            ->withGroupNameTranslation()
            ->withProductOptionValueTranslation()
            ->build();

        $idProductOptionGroup = $this->getProductOptionFacade()->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupTransfer->setIdProductOptionGroup($idProductOptionGroup);

        return $productOptionGroupTransfer;
    }

    /**
     * @param string $productAbstractSku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    public function haveProductOptionValueForAbstractProduct(
        string $productAbstractSku,
        StoreTransfer $storeTransfer
    ): ProductOptionValueTransfer {
        $currencyTransfer = $this->getDefaultStoreCurrency($storeTransfer);

        $productOptionGroupTransfer = (new ProductOptionGroupBuilder())
            ->withProductOptionValue([
                ProductOptionValueTransfer::PRICES => new ArrayObject([
                    [
                        MoneyValueTransfer::NET_AMOUNT => 100,
                        MoneyValueTransfer::GROSS_AMOUNT => 100,
                        MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStoreOrFail(),
                        MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrencyOrFail(),
                    ],
                ]),
            ])
            ->withGroupNameTranslation()
            ->withProductOptionValueTranslation()
            ->build();

        $idProductOptionGroup = $this->getProductOptionFacade()->saveProductOptionGroup($productOptionGroupTransfer);
        $productOptionGroupTransfer->setIdProductOptionGroup($idProductOptionGroup);

        $this->getProductOptionFacade()->addProductAbstractToProductOptionGroup(
            $productAbstractSku,
            $idProductOptionGroup,
        );

        return $productOptionGroupTransfer->getProductOptionValues()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getDefaultStoreCurrency(StoreTransfer $storeTransfer): CurrencyTransfer
    {
        $currencyTransfers = $this->getLocator()->currency()->facade()->getCurrencyTransfersByIsoCodes([
            $storeTransfer->getDefaultCurrencyIsoCodeOrFail(),
        ]);

        return array_shift($currencyTransfers);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface
     */
    protected function getProductOptionFacade(): ProductOptionFacadeInterface
    {
        return $this->getLocator()->productOption()->facade();
    }
}
