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
    protected const SKU_PRODUCT_ABSTRACT = 'Product abstract sku';
    protected const PRODUCT_ABSTRACT_ID = 777;
    protected const PRODUCT_URL_EN = '/en/product-1';
    protected const PRODUCT_URL_DE = '/de/product-1';
    protected const SKU_PRODUCT_CONCRETE = 'Product concrete sku';
    protected const PRODUCT_CONCRETE_NAME = 'Product concrete name';

    /**
     * @var \SprykerTest\Zed\ProductCartConnector\ProductCartConnectorCommunicationTester
     */
    protected $tester;

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
    }

    /**
     * @return void
     */
    public function testPluginExpandsCartItemWithExpectedProductUrl(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => static::PRODUCT_ABSTRACT_ID,
            ProductAbstractTransfer::SKU => static::SKU_PRODUCT_ABSTRACT,
        ]);

        $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => static::SKU_PRODUCT_CONCRETE,
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
        ]);

        $localeTransferEn = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => 'en_US']);
        $localeTransferDE = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => 'de_DE']);

        $enUrlTransfer = $this->tester->haveUrl([
            UrlTransfer::FK_LOCALE => $localeTransferEn->getIdLocale(),
            UrlTransfer::FK_RESOURCE_PRODUCT_ABSTRACT => static::PRODUCT_ABSTRACT_ID,
            UrlTransfer::URL => static::PRODUCT_URL_EN,
        ]);

        $deUrlTransfer = $this->tester->haveUrl([
            UrlTransfer::FK_LOCALE => $localeTransferDE->getIdLocale(),
            UrlTransfer::FK_RESOURCE_PRODUCT_ABSTRACT => static::PRODUCT_ABSTRACT_ID,
            UrlTransfer::URL => static::PRODUCT_URL_DE,
        ]);

        $emptyChangeTransfer = new CartChangeTransfer();
        $enChangeTransfer = new CartChangeTransfer();
        $deChangeTransfer = new CartChangeTransfer();

        $enItemTransfer = new ItemTransfer();
        $enItemTransfer->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID);

        $deItemTransfer = new ItemTransfer();
        $deItemTransfer->setIdProductAbstract(static::PRODUCT_ABSTRACT_ID);

        $enChangeTransfer->addItem($enItemTransfer);
        $deChangeTransfer->addItem($deItemTransfer);

        // Act
        $this->localeFacade->setCurrentLocale($localeTransferEn);
        $enChangeTransfer = $this->tester->getFacade()->expandItemTransfersWithUrl($enChangeTransfer);
        $expandedEnItemTransfer = $enChangeTransfer->getItems()[0];

        $this->localeFacade->setCurrentLocale($localeTransferDE);
        $deChangeTransfer = $this->tester->getFacade()->expandItemTransfersWithUrl($deChangeTransfer);
        $expandedDeItemTransfer = $deChangeTransfer->getItems()[0];

        $expandedEmptyItemTransfer = $this->tester->getFacade()->expandItemTransfersWithUrl($emptyChangeTransfer);

        //Assert
        $this->assertEquals($enUrlTransfer->getUrl(), $expandedEnItemTransfer->getUrl());
        $this->assertEquals($deUrlTransfer->getUrl(), $expandedDeItemTransfer->getUrl());
        $this->assertEquals(0, count($expandedEmptyItemTransfer->getItems()));
    }
}
