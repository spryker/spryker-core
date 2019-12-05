<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CmsSlotBlockProductCategoryConnector;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CmsSlotParamsBuilder;
use Generated\Shared\DataBuilder\ProductAbstractCategoryStorageBuilder;
use Generated\Shared\DataBuilder\ProductCategoryStorageBuilder;
use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\ProductCategoryStorageTransfer;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\CmsSlotBlockProductCategoryConnectorClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\CmsSlotBlockProductCategoryConnectorDependencyProvider;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface;
use Spryker\Shared\CmsSlotBlockProductCategoryConnector\CmsSlotBlockProductCategoryConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CmsSlotBlockProductCategoryConnector
 * @group CmsSlotBlockProductCategoryConnectorClientTest
 * Add your own group annotations below this line
 */
class CmsSlotBlockProductCategoryConnectorClientTest extends Unit
{
    protected const ID_PRODUCT_ABSTRACT = 1;
    protected const ID_CATEGORY = 4;

    /**
     * @var \SprykerTest\Client\CmsSlotBlockProductCategoryConnector\CmsSlotBlockProductCategoryConnectorClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsSlotBlockConditionApplicableReturnsTrueWithCorrectData(): void
    {
        // Arrange
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->addCondition(
            CmsSlotBlockProductCategoryConnectorConfig::CONDITION_KEY,
            new CmsSlotBlockConditionTransfer()
        );

        // Act
        $isSlotBlockConditionApplicable = $this->getCmsSlotBlockProductCategoryConnectorClient()
            ->isSlotBlockConditionApplicable($cmsSlotBlockTransfer);

        // Assert
        $this->assertTrue($isSlotBlockConditionApplicable);
    }

    /**
     * @return void
     */
    public function testIsSlotBlockConditionApplicableReturnsFalseWithIncorrectData(): void
    {
        // Arrange
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->addCondition(
            'incorrect-condition-key',
            new CmsSlotBlockConditionTransfer()
        );

        // Act
        $isSlotBlockConditionApplicable = $this->getCmsSlotBlockProductCategoryConnectorClient()
            ->isSlotBlockConditionApplicable($cmsSlotBlockTransfer);

        // Assert
        $this->assertFalse($isSlotBlockConditionApplicable);
    }

    /**
     * @return void
     */
    public function testIsCmsBlockVisibleInSlotReturnsTrueWithAllKeyProvided(): void
    {
        // Arrange
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->addCondition(
            CmsSlotBlockProductCategoryConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(true)
        );

        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_PRODUCT_ABSTRACT => static::ID_PRODUCT_ABSTRACT,
        ]);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockProductCategoryConnectorClient()
            ->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer);

        // Assert
        $this->assertTrue($isCmsBlockVisibleInSlot);
    }

    /**
     * @return void
     */
    public function testIsCmsBlockVisibleInSlotReturnsTrueWithCorrectProductData(): void
    {
        // Arrange
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->addCondition(
            CmsSlotBlockProductCategoryConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(false)
                ->setProductIds([static::ID_PRODUCT_ABSTRACT])
        );

        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_PRODUCT_ABSTRACT => static::ID_PRODUCT_ABSTRACT,
        ]);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockProductCategoryConnectorClient()
            ->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer);

        // Assert
        $this->assertTrue($isCmsBlockVisibleInSlot);
    }

    /**
     * @return void
     */
    public function testIsCmsBlockVisibleInSlotReturnsTrueWithCorrectCategoryData(): void
    {
        // Arrange
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->addCondition(
            CmsSlotBlockProductCategoryConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(false)
                ->setProductIds([static::ID_PRODUCT_ABSTRACT + 1])
                ->setCategoryIds([static::ID_CATEGORY])
        );
        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_PRODUCT_ABSTRACT => static::ID_PRODUCT_ABSTRACT,
        ]);
        $productCategoryStorageTransfer = $this->haveProductCategoryStorage([
            ProductCategoryStorageTransfer::CATEGORY_ID => static::ID_CATEGORY,
        ]);
        $productAbstractCategoryStorageTransfer = $this->haveProductAbstractCategoryStorage([
            ProductAbstractCategoryStorageTransfer::ID_PRODUCT_ABSTRACT => static::ID_PRODUCT_ABSTRACT,
        ]);
        $productAbstractCategoryStorageTransfer->addCategory($productCategoryStorageTransfer);
        $this->setProductCategoryStorageClientMock($productAbstractCategoryStorageTransfer);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockProductCategoryConnectorClient()
            ->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer);

        // Assert
        $this->assertTrue($isCmsBlockVisibleInSlot);
    }

    /**
     * @return void
     */
    public function testIsCmsBlockVisibleInSlotReturnsFalseWithIncorrectData(): void
    {
        // Arrange
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->addCondition(
            CmsSlotBlockProductCategoryConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(false)
                ->setProductIds([static::ID_PRODUCT_ABSTRACT + 1])
                ->setCategoryIds([static::ID_CATEGORY + 1])
        );
        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_PRODUCT_ABSTRACT => static::ID_PRODUCT_ABSTRACT,
        ]);
        $productCategoryStorageTransfer = $this->haveProductCategoryStorage([
            ProductCategoryStorageTransfer::CATEGORY_ID => static::ID_CATEGORY,
        ]);
        $productAbstractCategoryStorageTransfer = $this->haveProductAbstractCategoryStorage([
            ProductAbstractCategoryStorageTransfer::ID_PRODUCT_ABSTRACT => static::ID_PRODUCT_ABSTRACT,
        ]);
        $productAbstractCategoryStorageTransfer->addCategory($productCategoryStorageTransfer);
        $this->setProductCategoryStorageClientMock($productAbstractCategoryStorageTransfer);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockProductCategoryConnectorClient()
            ->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer);

        // Assert
        $this->assertFalse($isCmsBlockVisibleInSlot);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
     *
     * @return void
     */
    protected function setProductCategoryStorageClientMock(
        ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
    ): void {
        $productCategoryStorageClientMock = $this
            ->getMockBuilder(CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface::class)
            ->getMock();
        $productCategoryStorageClientMock->expects($this->once())
            ->method('findProductAbstractCategory')
            ->willReturn($productAbstractCategoryStorageTransfer);
        $this->tester->setDependency(
            CmsSlotBlockProductCategoryConnectorDependencyProvider::CLIENT_PRODUCT_CATEGORY_STORAGE,
            $productCategoryStorageClientMock
        );
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockProductCategoryConnector\CmsSlotBlockProductCategoryConnectorClientInterface
     */
    public function getCmsSlotBlockProductCategoryConnectorClient(): CmsSlotBlockProductCategoryConnectorClientInterface
    {
        return $this->tester
            ->getLocator()
            ->cmsSlotBlockProductCategoryConnector()
            ->client();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotParamsTransfer
     */
    public function haveCmsSlotParams(array $seedData = []): CmsSlotParamsTransfer
    {
        return (new CmsSlotParamsBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer
     */
    public function haveProductAbstractCategoryStorage(array $seedData = []): ProductAbstractCategoryStorageTransfer
    {
        return (new ProductAbstractCategoryStorageBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductCategoryStorageTransfer
     */
    public function haveProductCategoryStorage(array $seedData = []): ProductCategoryStorageTransfer
    {
        return (new ProductCategoryStorageBuilder($seedData))->build();
    }
}
