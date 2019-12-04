<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CmsSlotBlockCmsConnector;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CmsSlotParamsBuilder;
use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
use Spryker\Client\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorClientInterface;
use Spryker\Shared\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CmsSlotBlockCmsConnector
 * @group CmsSlotBlockCmsConnectorClientTest
 * Add your own group annotations below this line
 */
class CmsSlotBlockCmsConnectorClientTest extends Unit
{
    protected const ID_CMS_PAGE = 1;

    /**
     * @var \SprykerTest\Client\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsSlotBlockConditionApplicableReturnsTrueWithCorrectData(): void
    {
        // Arrange
        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->addCondition(
            CmsSlotBlockCmsConnectorConfig::CONDITION_KEY,
            new CmsSlotBlockConditionTransfer()
        );

        // Act
        $isSlotBlockConditionApplicable = $this->getCmsSlotBlockCmsConnectorClient()
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
        $isSlotBlockConditionApplicable = $this->getCmsSlotBlockCmsConnectorClient()
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
            CmsSlotBlockCmsConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(true)
        );

        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_CMS_PAGE => static::ID_CMS_PAGE,
        ]);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockCmsConnectorClient()
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
            CmsSlotBlockCmsConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(false)
                ->setCmsPageIds([static::ID_CMS_PAGE])
        );
        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_CMS_PAGE => static::ID_CMS_PAGE,
        ]);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockCmsConnectorClient()
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
            CmsSlotBlockCmsConnectorConfig::CONDITION_KEY,
            (new CmsSlotBlockConditionTransfer())->setAll(false)
                ->setCmsPageIds([static::ID_CMS_PAGE + 1])
        );
        $cmsSlotParamsTransfer = $this->haveCmsSlotParams([
            CmsSlotParamsTransfer::ID_CMS_PAGE => static::ID_CMS_PAGE,
        ]);

        // Act
        $isCmsBlockVisibleInSlot = $this->getCmsSlotBlockCmsConnectorClient()
            ->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer);

        // Assert
        $this->assertFalse($isCmsBlockVisibleInSlot);
    }

    /**
     * @return \Spryker\Client\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorClientInterface
     */
    public function getCmsSlotBlockCmsConnectorClient(): CmsSlotBlockCmsConnectorClientInterface
    {
        return $this->tester
            ->getLocator()
            ->cmsSlotBlockCmsConnector()
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
}
