<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CmsSlotBlockCategoryConnector;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CmsSlotParamsBuilder;
use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
use Spryker\Client\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorClientInterface;
use Spryker\Shared\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CmsSlotBlockCategoryConnector
 * @group CmsSlotBlockCategoryConnectorClientTest
 * Add your own group annotations below this line
 */
class CmsSlotBlockCategoryConnectorClientTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_CATEGORY = 1;

    /**
     * @var \SprykerTest\Client\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsSlotBlockConditionApplicableReturnsTrueWithCorrectData(): void
    {
        // Arrange
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->addCondition(
            CmsSlotBlockCategoryConnectorConfig::CONDITION_KEY,
            new CmsSlotBlockConditionTransfer(),
        );

        // Act
        $isSlotBlockConditionApplicable = $this->getCmsSlotBlockCategoryConnectorClient()
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
            new CmsSlotBlockConditionTransfer(),
        );

        // Act
        $isSlotBlockConditionApplicable = $this->getCmsSlotBlockCategoryConnectorClient()
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
            CmsSlotBlockCategoryConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(true),
        );
        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_CATEGORY => static::ID_CATEGORY,
        ]);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockCategoryConnectorClient()
            ->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer);

        // Assert
        $this->assertTrue($isCmsBlockVisibleInSlot);
    }

    /**
     * @return void
     */
    public function testIsCmsBlockVisibleInSlotReturnsTrueWithCorrectData(): void
    {
        // Arrange
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->addCondition(
            CmsSlotBlockCategoryConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(false)
            ->setCategoryIds([static::ID_CATEGORY]),
        );
        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_CATEGORY => static::ID_CATEGORY,
        ]);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockCategoryConnectorClient()
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
            CmsSlotBlockCategoryConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(false)
                ->setCategoryIds([static::ID_CATEGORY + 1]),
        );
        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_CATEGORY => static::ID_CATEGORY,
        ]);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockCategoryConnectorClient()
            ->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer);

        // Assert
        $this->assertFalse($isCmsBlockVisibleInSlot);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotParamsTransfer
     */
    protected function haveCmsSlotParams(array $seedData = []): CmsSlotParamsTransfer
    {
        return (new CmsSlotParamsBuilder($seedData))->build();
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorClientInterface
     */
    protected function getCmsSlotBlockCategoryConnectorClient(): CmsSlotBlockCategoryConnectorClientInterface
    {
        return $this->tester
            ->getLocator()
            ->cmsSlotBlockCategoryConnector()
            ->client();
    }
}
