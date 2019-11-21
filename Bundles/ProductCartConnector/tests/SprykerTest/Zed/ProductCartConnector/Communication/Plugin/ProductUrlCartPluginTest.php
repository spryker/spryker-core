<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\UrlTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCartConnector
 * @group Communication
 * @group Plugin
 * @group ProductUrlCartPluginTest
 * Add your own group annotations below this line
 */
class ProductUrlCartPluginTest extends Unit
{
    public const SKU_PRODUCT_ABSTRACT = 'Product abstract sku';
    public const PRODUCT_ABSTRACT_ID = 777;
    public const PRODUCT_URL_EN = '/en/product-1';
    public const PRODUCT_URL_DE = '/de/product-1';
    public const SKU_PRODUCT_CONCRETE = 'Product concrete sku';
    public const PRODUCT_CONCRETE_NAME = 'Product concrete name';

    /**
     * @var \SprykerTest\Zed\ProductCartConnector\ProductCartConnectorCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacade
     */
    protected $productCartConnectorFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->localeFacade = $this->tester->getLocator()->locale()->facade();
        $this->productCartConnectorFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function _before()
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => self::PRODUCT_ABSTRACT_ID,
            ProductAbstractTransfer::SKU => self::SKU_PRODUCT_ABSTRACT,
        ]);

        $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => self::SKU_PRODUCT_CONCRETE,
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
        ]);
    }

    /**
     * @return void
     */
    public function testPluginExpandsCartItemWithExpectedProductUrl(): void
    {
        // Arrange
        $localeTransferEn = $this->tester->haveLocale(['localeName' => 'en_US']);
        $localeTransferDE = $this->tester->haveLocale(['localeName' => 'de_DE']);

        $enUrlTransfer = $this->tester->haveUrl([
            UrlTransfer::FK_LOCALE => $localeTransferEn->getIdLocale(),
            UrlTransfer::FK_RESOURCE_PRODUCT_ABSTRACT => self::PRODUCT_ABSTRACT_ID,
            UrlTransfer::URL => self::PRODUCT_URL_EN,
        ]);

        $deUrlTransfer = $this->tester->haveUrl([
            UrlTransfer::FK_LOCALE => $localeTransferDE->getIdLocale(),
            UrlTransfer::FK_RESOURCE_PRODUCT_ABSTRACT => self::PRODUCT_ABSTRACT_ID,
            UrlTransfer::URL => self::PRODUCT_URL_DE,
        ]);

        $changeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setIdProductAbstract(self::PRODUCT_ABSTRACT_ID);
        $changeTransfer->addItem($itemTransfer);

        $this->checkUrlForLocales($localeTransferEn, $changeTransfer, $enUrlTransfer);
        $this->checkUrlForLocales($localeTransferDE, $changeTransfer, $deUrlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $changeTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function checkUrlForLocales(LocaleTransfer $localeTransfer, CartChangeTransfer $changeTransfer, UrlTransfer $urlTransfer): void
    {
        // Act
        $this->localeFacade->setCurrentLocale($localeTransfer);

        $this->productCartConnectorFacade->expandItemTransfersWithUrl($changeTransfer);
        $expandedENItemTransfer = $changeTransfer->getItems()[0];

        // Assert
        $this->assertEquals($urlTransfer->getUrl(), $expandedENItemTransfer->getUrl());
    }
}
