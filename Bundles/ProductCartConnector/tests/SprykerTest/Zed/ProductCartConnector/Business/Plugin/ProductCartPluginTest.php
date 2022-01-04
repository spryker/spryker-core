<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector\Business\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCartConnector
 * @group Business
 * @group Plugin
 * @group ProductCartPluginTest
 * Add your own group annotations below this line
 */
class ProductCartPluginTest extends Unit
{
    /**
     * @var string
     */
    public const SKU_PRODUCT_ABSTRACT = 'Product abstract sku';

    /**
     * @var string
     */
    public const SKU_PRODUCT_CONCRETE = 'Product concrete sku';

    /**
     * @var string
     */
    public const TAX_SET_NAME = 'Sales Tax';

    /**
     * @var string
     */
    public const TAX_RATE_NAME = 'VAT';

    /**
     * @var int
     */
    public const TAX_RATE_PERCENTAGE = 10;

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_NAME = 'Product concrete name';

    /**
     * @var \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacade
     */
    protected $productCartConnectorFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->localeFacade = new LocaleFacade();
        $this->productCartConnectorFacade = new ProductCartConnectorFacade();
    }

    /**
     * @return void
     */
    public function testPluginExpandsCartItemWithExpectedProductData(): void
    {
        $localeName = $this->localeFacade->getCurrentLocale()->getLocaleNameOrFail();
        $localeTransfer = $this->localeFacade->getLocale($localeName);

        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setRate(static::TAX_RATE_PERCENTAGE)
            ->setName(static::TAX_RATE_NAME);

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->addSpyTaxRate($taxRateEntity)
            ->setName(static::TAX_SET_NAME);

        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity->setSpyTaxSet($taxSetEntity)
            ->setAttributes('')
            ->setSku(static::SKU_PRODUCT_ABSTRACT);

        $localizedAttributesEntity = new SpyProductLocalizedAttributes();
        $localizedAttributesEntity->setName(static::PRODUCT_CONCRETE_NAME)
            ->setAttributes('')
            ->setFkLocale($localeTransfer->getIdLocale());

        $productConcreteEntity = new SpyProduct();
        $productConcreteEntity->setSpyProductAbstract($productAbstractEntity)
            ->setAttributes('')
            ->addSpyProductLocalizedAttributes($localizedAttributesEntity)
            ->setSku(static::SKU_PRODUCT_CONCRETE)
            ->save();

        $changeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku(static::SKU_PRODUCT_CONCRETE);
        $changeTransfer->addItem($itemTransfer);

        $this->productCartConnectorFacade->expandItems($changeTransfer);

        $expandedItemTransfer = $changeTransfer->getItems()[0];

        $this->assertSame(static::SKU_PRODUCT_ABSTRACT, $expandedItemTransfer->getAbstractSku());
        $this->assertSame(static::SKU_PRODUCT_CONCRETE, $expandedItemTransfer->getSku());
        $this->assertSame($productAbstractEntity->getIdProductAbstract(), $expandedItemTransfer->getIdProductAbstract());
        $this->assertSame($productConcreteEntity->getIdProduct(), $expandedItemTransfer->getId());
    }
}
