<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductCartConnectorBusinessTester extends Actor
{
    use _generated\ProductCartConnectorBusinessTesterActions;

    protected const SKU_PRODUCT_ABSTRACT = 'Product abstract sku';
    protected const PRODUCT_ABSTRACT_ID = 777;
    protected const PRODUCT_URL_EN = '/en/product-1';
    protected const PRODUCT_URL_DE = '/de/product-1';
    protected const SKU_PRODUCT_CONCRETE = 'Product concrete sku';

    /**
     * @return void
     */
    public function setUpDatabase(): void
    {
        $this->insertProducts();

        $localeTransfer = $this->haveLocale([LocaleTransfer::LOCALE_NAME => 'en_US']);

        $this->getLocaleFacade()->setCurrentLocale($localeTransfer);
    }

    /**
     * @return void
     */
    protected function insertProducts(): void
    {
        $productAbstractTransfer = $this->haveProductAbstract([
            ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => static::PRODUCT_ABSTRACT_ID,
            ProductAbstractTransfer::SKU => static::SKU_PRODUCT_ABSTRACT,
        ]);

        $this->haveProduct([
            ProductConcreteTransfer::SKU => static::SKU_PRODUCT_CONCRETE,
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createProductUrl(): UrlTransfer
    {
        return $this->haveUrl([
            UrlTransfer::FK_LOCALE => $this->getLocaleFacade()->getCurrentLocale()->getIdLocale(),
            UrlTransfer::FK_RESOURCE_PRODUCT_ABSTRACT => static::PRODUCT_ABSTRACT_ID,
            UrlTransfer::URL => static::PRODUCT_URL_EN,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeWithProduct(): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID);

        $cartChangeTransfer->addItem($itemTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }
}
