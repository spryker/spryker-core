<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImageStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\LocalizedAttributesBuilder;
use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Spryker\Client\Kernel\Container;
use SprykerTest\Zed\ProductImageStorage\ProductImageStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImageStorage
 * @group Business
 * @group Facade
 * @group ProductImageStorageFacadeTest
 * Add your own group annotations below this line
 */
class ProductImageStorageFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Client\Queue\QueueDependencyProvider::QUEUE_ADAPTERS
     *
     * @var string
     */
    protected const QUEUE_ADAPTERS = 'queue adapters';

    /**
     * @var string
     */
    protected const LOCALE_US = 'en_US';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var \SprykerTest\Zed\ProductImageStorage\ProductImageStorageBusinessTester
     */
    protected ProductImageStorageBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $this->tester->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    public function testUnpublishRemovesProductImageAbstractStoragesWhenProductAbstractDoesNotHaveLocalizedAttributes(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_US]);
        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $this->tester->haveProductAbstractImageStorage($localeTransfer, [
            ProductAbstractImageStorageTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->unpublishProductAbstractImages([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $this->assertCount(
            0,
            $this->tester->findProductAbstractImageStorageCollectionByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail()),
        );
    }

    /**
     * @return void
     */
    public function testUnpublishRemovesCorrectProductImageAbstractStoragesWhenProductAbstractHaveLocalizedAttributeRemovedForLocale(): void
    {
        // Arrange
        $localeEnTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_US]);
        $localeDeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE]);
        $productAbstractTransfer = (new ProductAbstractBuilder())->withLocalizedAttributes(
            (new LocalizedAttributesBuilder([LocalizedAttributesTransfer::LOCALE => $localeDeTransfer])),
        )->build();
        $productAbstractTransfer = $this->tester->haveProductAbstract($productAbstractTransfer->toArray());

        $this->tester->haveProductAbstractImageStorage($localeEnTransfer, [
            ProductAbstractImageStorageTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
        ]);
        $this->tester->haveProductAbstractImageStorage($localeDeTransfer, [
            ProductAbstractImageStorageTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
        ]);

        // Act
        $this->tester->getFacade()->unpublishProductAbstractImages([$productAbstractTransfer->getIdProductAbstractOrFail()]);

        // Assert
        $productAbstractImageStorageEntities = $this->tester->findProductAbstractImageStorageCollectionByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
        );
        $this->assertCount(1, $productAbstractImageStorageEntities);
        $this->assertSame($localeEnTransfer->getLocaleNameOrFail(), $productAbstractImageStorageEntities[0]->getLocale());
    }
}
