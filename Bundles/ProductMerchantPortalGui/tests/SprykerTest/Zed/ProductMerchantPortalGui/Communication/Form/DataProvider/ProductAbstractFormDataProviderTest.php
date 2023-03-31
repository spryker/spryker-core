<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantUserBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantPortalGui
 * @group Communication
 * @group Form
 * @group DataProvider
 * @group ProductAbstractFormDataProviderTest
 * Add your own group annotations below this line
 */
class ProductAbstractFormDataProviderTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm::OPTION_STORE_CHOICES
     *
     * @var string
     */
    protected const OPTION_STORE_CHOICES = 'OPTION_STORE_CHOICES';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm::OPTION_PRODUCT_CATEGORY_CHOICES
     *
     * @var string
     */
    protected const OPTION_PRODUCT_CATEGORY_CHOICES = 'OPTION_PRODUCT_CATEGORY_CHOICES';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester
     */
    protected ProductMerchantPortalGuiCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGetOptionsShouldReturnRequiredProductAbstractFormOptions(): void
    {
        // Arrange
        $merchantUserTransfer = (new MerchantUserBuilder())
            ->withMerchant([
                MerchantTransfer::ID_MERCHANT => 1,
            ])
            ->build();

        $merchantUserFacade = $this->tester->createMerchantUserFacadeMock([
            'getCurrentMerchantUser' => function () use ($merchantUserTransfer) {
                return $merchantUserTransfer;
            },
        ]);

        $productOfferCreateFormDataProvider = $this->createProductAbstractFormDataProvider($merchantUserFacade);

        // Act
        $options = $productOfferCreateFormDataProvider->getOptions();

        // Assert
        $this->assertArrayHasKey(static::OPTION_STORE_CHOICES, $options);
        $this->assertArrayHasKey(static::OPTION_PRODUCT_CATEGORY_CHOICES, $options);
    }

    /**
     * @return void
     */
    public function testGetOptionsShouldReturnOnlyValidStoreChoicesForCurrentMerchantUser(): void
    {
        // Arrange
        $merchantUserRelatedStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $anotherStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);

        $storeRelationTransfer = (new StoreRelationBuilder())
            ->withStores($merchantUserRelatedStoreTransfer->toArray())
            ->build();

        $merchantUserTransfer = (new MerchantUserBuilder())
            ->withMerchant([
                MerchantTransfer::ID_MERCHANT => 1,
                MerchantTransfer::STORE_RELATION => $storeRelationTransfer,
            ])
            ->build();

        $merchantUserFacade = $this->tester->createMerchantUserFacadeMock([
            'getCurrentMerchantUser' => function () use ($merchantUserTransfer) {
                return $merchantUserTransfer;
            },
        ]);

        $productOfferCreateFormDataProvider = $this->createProductAbstractFormDataProvider($merchantUserFacade);

        // Act
        $options = $productOfferCreateFormDataProvider->getOptions();

        // Assert
        $this->assertCount(1, $options[static::OPTION_STORE_CHOICES]);
        $this->assertArrayHasKey($merchantUserRelatedStoreTransfer->getNameOrFail(), $options[static::OPTION_STORE_CHOICES]);
        $this->assertSame($options[static::OPTION_STORE_CHOICES][$merchantUserRelatedStoreTransfer->getNameOrFail()], $merchantUserRelatedStoreTransfer->getIdStoreOrFail());
    }

    /**
     * @return void
     */
    public function testGetOptionsShouldReturnEmptyStoreChoicesForCurrentMerchantWhileNoStoreRelationsGiven(): void
    {
        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);

        $merchantUserTransfer = (new MerchantUserBuilder())
            ->withMerchant([
                MerchantTransfer::ID_MERCHANT => 1,
            ])
            ->build();

        $merchantUserFacade = $this->tester->createMerchantUserFacadeMock([
            'getCurrentMerchantUser' => function () use ($merchantUserTransfer) {
                return $merchantUserTransfer;
            },
        ]);

        $productOfferCreateFormDataProvider = $this->createProductAbstractFormDataProvider($merchantUserFacade);

        // Act
        $options = $productOfferCreateFormDataProvider->getOptions();

        // Assert
        $this->assertEmpty($options[static::OPTION_STORE_CHOICES]);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    protected function createMerchantUserTransfer(): MerchantUserTransfer
    {
        $storeRelationTransfer = (new StoreRelationBuilder())
            ->withStores($this->tester->haveStore()->toArray())
            ->build();

        return (new MerchantUserBuilder())
            ->withMerchant([
                MerchantTransfer::ID_MERCHANT => 1,
                MerchantTransfer::STORE_RELATION => $storeRelationTransfer,
            ])
            ->build();
    }

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     *
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductAbstractFormDataProvider
     */
    protected function createProductAbstractFormDataProvider(
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ): ProductAbstractFormDataProvider {
        return new ProductAbstractFormDataProvider(
            $this->tester->getFactory()->getMerchantProductFacade(),
            $merchantUserFacade,
            $this->tester->getFactory()->getCategoryFacade(),
            $this->tester->getFactory()->getLocaleFacade(),
            $this->tester->getFactory()->getProductCategoryFacade(),
            $this->tester->getFactory()->getConfig(),
        );
    }
}
