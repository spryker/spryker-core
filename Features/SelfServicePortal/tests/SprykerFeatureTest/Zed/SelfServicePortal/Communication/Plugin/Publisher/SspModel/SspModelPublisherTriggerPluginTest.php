<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspModel;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SspModelTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspModelPublisherTriggerPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group SspModel
 * @group SspModelPublisherTriggerPluginTest
 * Add your own group annotations below this line
 */
class SspModelPublisherTriggerPluginTest extends Unit
{
    /**
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::SSP_MODEL_RESOURCE_NAME
     *
     * @var string
     */
    protected const SSP_MODEL_RESOURCE_NAME = 'ssp_model';

    /**
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::SSP_MODEL_PUBLISH
     *
     * @var string
     */
    protected const SSP_MODEL_PUBLISH = 'SspModel.ssp_model.publish';

    /**
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspModelTableMap::COL_ID_SSP_MODEL
     *
     * @var string
     */
    protected const COL_ID_SSP_MODEL = 'spy_ssp_model.id_ssp_model';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->clearSspModelData();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->clearSspModelData();
    }

    public function testGetDataReturnsSspModelTransfersAccordingToOffsetAndLimit(): void
    {
        // Arrange
        $this->tester->clearSspModelData();

        $firstModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'First Model',
        ]);
        $secondModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Second Model',
        ]);
        $thirdModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Third Model',
        ]);

        // Act
        $allModels = (new SspModelPublisherTriggerPlugin())->getData(0, 10);
        $paginatedModels = (new SspModelPublisherTriggerPlugin())->getData(1, 2);

        // Assert
        $this->assertGreaterThanOrEqual(3, count($allModels));

        $this->assertCount(2, $paginatedModels);
        $this->assertInstanceOf(SspModelTransfer::class, $paginatedModels[0]);
        $this->assertInstanceOf(SspModelTransfer::class, $paginatedModels[1]);

        $this->assertNotEquals($allModels[0]->getIdSspModel(), $paginatedModels[0]->getIdSspModel());
    }

    public function testGetDataReturnsNoSspModelTransfersWhenLimitIsEqualToZero(): void
    {
        // Arrange
        $this->tester->clearSspModelData();

        $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Test Model',
        ]);

        // Act
        $sspModelTransfers = (new SspModelPublisherTriggerPlugin())->getData(0, 0);

        // Assert
        $this->assertCount(0, $sspModelTransfers);
    }

    public function testGetResourceNameReturnsCorrectResourceName(): void
    {
        // Act
        $resourceName = (new SspModelPublisherTriggerPlugin())->getResourceName();

        // Assert
        $this->assertSame(static::SSP_MODEL_RESOURCE_NAME, $resourceName);
    }

    public function testGetEventNameReturnsCorrectEventName(): void
    {
        // Act
        $eventName = (new SspModelPublisherTriggerPlugin())->getEventName();

        // Assert
        $this->assertSame(static::SSP_MODEL_PUBLISH, $eventName);
    }

    public function testGetIdColumnNameReturnsCorrectColumnName(): void
    {
        // Act
        $idColumnName = (new SspModelPublisherTriggerPlugin())->getIdColumnName();

        // Assert
        $this->assertSame(static::COL_ID_SSP_MODEL, $idColumnName);
    }
}
