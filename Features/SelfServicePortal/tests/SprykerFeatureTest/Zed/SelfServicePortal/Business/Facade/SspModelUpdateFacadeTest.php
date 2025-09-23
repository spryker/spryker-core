<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ModelSspAssetAssignmentTransfer;
use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToSspModelQuery;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group SspModelUpdateFacadeTest
 * Add your own group annotations below this line
 */
class SspModelUpdateFacadeTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface
     */
    protected $selfServicePortalFacade;

    protected function setUp(): void
    {
        parent::setUp();

        $this->selfServicePortalFacade = new SelfServicePortalFacade();
    }

    public function testUpdateSspModelCollectionWithMultipleModels(): void
    {
        // Arrange
        $sspModelTransfer1 = $this->tester->haveSspModel();
        $sspModelTransfer2 = $this->tester->haveSspModel();
        $sspAssetTransfer1 = $this->tester->haveAsset();
        $sspAssetTransfer2 = $this->tester->haveAsset();
        $sspAssetTransfer3 = $this->tester->haveAsset();

        $this->tester->assignSspAssetsToSspModel($sspModelTransfer1, [$sspAssetTransfer1]);
        $this->tester->assignSspAssetsToSspModel($sspModelTransfer2, [$sspAssetTransfer1, $sspAssetTransfer2]);

        $sspModelCollectionRequestTransfer = (new SspModelCollectionRequestTransfer());

        $sspModelCollectionRequestTransfer->addSspAssetToBeAssigned((new ModelSspAssetAssignmentTransfer())
                ->setSspAsset($sspAssetTransfer1)
                ->setSspModel($sspModelTransfer1))
            ->addSspAssetToBeAssigned((new ModelSspAssetAssignmentTransfer())
                ->setSspAsset($sspAssetTransfer2)
                ->setSspModel($sspModelTransfer1));

        $sspModelCollectionRequestTransfer->addSspAssetToBeAssigned((new ModelSspAssetAssignmentTransfer())
            ->setSspAsset($sspAssetTransfer3)
            ->setSspModel($sspModelTransfer2));
        $sspModelCollectionRequestTransfer->addSspAssetToBeUnassigned((new ModelSspAssetAssignmentTransfer())
            ->setSspAsset($sspAssetTransfer1)
            ->setSspModel($sspModelTransfer2));

        // Act
        $sspModelCollectionResponseTransfer = $this->selfServicePortalFacade->updateSspModelCollection(
            $sspModelCollectionRequestTransfer,
        );

        // Assert
        $this->assertTrue($sspModelCollectionResponseTransfer->getErrors()->count() === 0);
        $this->assertSame(2, SpySspAssetToSspModelQuery::create()->filterByFkSspModel($sspModelTransfer1->getIdSspModel())->count());
        $this->assertSame(2, SpySspAssetToSspModelQuery::create()->filterByFkSspModel($sspModelTransfer2->getIdSspModel())->count());
        $this->assertTrue($this->tester->hasSspModelAssetRelation($sspModelTransfer1->getIdSspModel(), $sspAssetTransfer1->getIdSspAsset()));
        $this->assertTrue($this->tester->hasSspModelAssetRelation($sspModelTransfer1->getIdSspModel(), $sspAssetTransfer2->getIdSspAsset()));
        $this->assertTrue($this->tester->hasSspModelAssetRelation($sspModelTransfer2->getIdSspModel(), $sspAssetTransfer2->getIdSspAsset()));
        $this->assertTrue($this->tester->hasSspModelAssetRelation($sspModelTransfer2->getIdSspModel(), $sspAssetTransfer3->getIdSspAsset()));
        $this->assertFalse($this->tester->hasSspModelAssetRelation($sspModelTransfer2->getIdSspModel(), $sspAssetTransfer1->getIdSspAsset()));
        $this->assertFalse($this->tester->hasSspModelAssetRelation($sspModelTransfer1->getIdSspModel(), $sspAssetTransfer3->getIdSspAsset()));
    }
}
