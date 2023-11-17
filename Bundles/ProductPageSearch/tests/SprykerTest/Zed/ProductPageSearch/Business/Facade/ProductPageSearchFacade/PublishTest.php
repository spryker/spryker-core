<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Business\Facade\ProductPageSearchFacade;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander\PricePageDataLoaderExpanderPlugin;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataLoader\PriceProductPageDataLoaderPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;
use Spryker\Zed\ProductSearch\Business\ProductSearchFacade;
use SprykerTest\Zed\ProductPageSearch\ProductPageSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Business
 * @group Facade
 * @group ProductPageSearchFacade
 * @group PublishTest
 * Add your own group annotations below this line
 */
class PublishTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_AT = 'AT';

    /**
     * @var int
     */
    protected const GROSS_PRICE_DE = 100;

    /**
     * @var int
     */
    protected const GROSS_PRICE_AT = 101;

    /**
     * @var \SprykerTest\Zed\ProductPageSearch\ProductPageSearchBusinessTester
     */
    protected ProductPageSearchBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldNotOverwriteDifferentStoreDataWithSameLocale(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_PAGE_DATA_LOADER,
            [
                new PriceProductPageDataLoaderPlugin(),
            ],
        );
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
        $this->tester->setDependency(
            ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_PAGE_DATA_EXPANDER,
            [
                ProductPageSearchConfig::PLUGIN_PRODUCT_PRICE_PAGE_DATA => new PricePageDataLoaderExpanderPlugin(),
            ],
        );
        $this->tester->mockConfigMethod('isSendingToQueue', false);
        $this->tester->setDependency(ProductPageSearchDependencyProvider::FACADE_SEARCH, Stub::make(
            ProductPageSearchToSearchBridge::class,
            [
                'transformPageMapToDocumentByMapperName' => function () {
                    return [];
                },
            ],
        ));

        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_DE]);
        $storeTransferAT = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_AT]);

        $productConcreteTransfer = $this->tester->haveFullProduct();
        $productAbstractTransfer = $this->tester->getProductFacade()->findProductAbstractById(
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $localizedAttributes = $this->tester->generateLocalizedAttributes();
        $this->tester->addLocalizedAttributesToProductAbstract($productAbstractTransfer, $localizedAttributes);
        $this->tester->addLocalizedAttributesToProductConcrete($productConcreteTransfer, $localizedAttributes);

        $locale = $this->tester->getLocator()->locale()->facade()->getCurrentLocale();
        $this->categoryTransfer = $this->tester->haveLocalizedCategory(['locale' => $locale]);
        (new ProductSearchFacade())->activateProductSearch($productConcreteTransfer->getIdProductConcrete(), [$locale]);

        $this->productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $priceProductOverrideDE = [
            PriceProductTransfer::ID_PRICE_PRODUCT => $productAbstractTransfer->getIdProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstractTransfer->getSku(),
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::GROSS_AMOUNT => static::GROSS_PRICE_DE,
                MoneyValueTransfer::STORE => $storeTransferDE,
            ],
        ];
        $this->priceProductTransfer = $this->tester->havePriceProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail(), $priceProductOverrideDE);

        $priceProductOverrideAT = [
            PriceProductTransfer::ID_PRICE_PRODUCT => $productAbstractTransfer->getIdProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productAbstractTransfer->getSku(),
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::GROSS_AMOUNT => static::GROSS_PRICE_AT,
                MoneyValueTransfer::STORE => $storeTransferAT,
            ],
        ];
        $this->priceProductTransfer = $this->tester->havePriceProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail(), $priceProductOverrideAT);

        // Act
        $this->tester->getFacade()->publish([$productAbstractTransfer->getIdProductAbstract()]);

        // Assert
        $productAbstractPageSearchTransfer = $this->tester->findProductPageSearchTransfer($productAbstractTransfer->getIdProductAbstract(), static::STORE_DE);
        $this->assertSame(static::GROSS_PRICE_DE, $productAbstractPageSearchTransfer->getPriceOrFail());
    }
}
