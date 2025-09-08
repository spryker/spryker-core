<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SspModelCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Facade
 * @group DeleteSspModelCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSspModelCollectionTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected SelfServicePortalBusinessTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureSspModelDatabaseTableIsEmpty();
    }

    public function testDeleteSspModelCollectionDeletesSingleModelById(): void
    {
        // Arrange
        $sspModelTransferToDelete = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Model to Delete',
            SspModelTransfer::CODE => 'DELETE_ME',
        ]);
        $sspModelTransferToKeep = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Model to Keep',
            SspModelTransfer::CODE => 'KEEP_ME',
        ]);

        $sspModelCollectionDeleteCriteriaTransfer = (new SspModelCollectionDeleteCriteriaTransfer())
            ->addIdSspModel($sspModelTransferToDelete->getIdSspModelOrFail());

        // Act
        $sspModelCollectionResponseTransfer = $this->tester->getFacade()
            ->deleteSspModelCollection($sspModelCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspModelCollectionResponseTransfer->getSspModels());
        $this->assertSame(
            $sspModelTransferToDelete->getIdSspModel(),
            $sspModelCollectionResponseTransfer->getSspModels()->getIterator()->current()->getIdSspModel(),
        );

        $remainingSspModelEntities = $this->tester->getSspModelEntities();
        $this->assertCount(1, $remainingSspModelEntities);
        $this->assertSame(
            $sspModelTransferToKeep->getIdSspModel(),
            $remainingSspModelEntities[0]->getIdSspModel(),
        );
    }

    public function testDeleteSspModelCollectionDeletesMultipleModelsByIds(): void
    {
        // Arrange
        $sspModelTransfer1 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Model 1',
            SspModelTransfer::CODE => 'MODEL_1',
        ]);
        $sspModelTransfer2 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Model 2',
            SspModelTransfer::CODE => 'MODEL_2',
        ]);
        $sspModelTransferToKeep = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Model to Keep',
            SspModelTransfer::CODE => 'KEEP_ME',
        ]);

        $sspModelCollectionDeleteCriteriaTransfer = (new SspModelCollectionDeleteCriteriaTransfer())
            ->addIdSspModel($sspModelTransfer1->getIdSspModelOrFail())
            ->addIdSspModel($sspModelTransfer2->getIdSspModelOrFail());

        // Act
        $sspModelCollectionResponseTransfer = $this->tester->getFacade()
            ->deleteSspModelCollection($sspModelCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(2, $sspModelCollectionResponseTransfer->getSspModels());

        $deletedModelIds = [];
        foreach ($sspModelCollectionResponseTransfer->getSspModels() as $deletedModel) {
            $deletedModelIds[] = $deletedModel->getIdSspModel();
        }

        $this->assertContains($sspModelTransfer1->getIdSspModel(), $deletedModelIds);
        $this->assertContains($sspModelTransfer2->getIdSspModel(), $deletedModelIds);

        $remainingSspModelEntities = $this->tester->getSspModelEntities();
        $this->assertCount(1, $remainingSspModelEntities);
        $this->assertSame(
            $sspModelTransferToKeep->getIdSspModel(),
            $remainingSspModelEntities[0]->getIdSspModel(),
        );
    }

    public function testDeleteSspModelCollectionDoesNotDeleteWhenNoEntitiesFound(): void
    {
        // Arrange
        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Existing Model',
            SspModelTransfer::CODE => 'EXISTING',
        ]);

        $sspModelCollectionDeleteCriteriaTransfer = (new SspModelCollectionDeleteCriteriaTransfer())
            ->addIdSspModel(-1);

        // Act
        $sspModelCollectionResponseTransfer = $this->tester->getFacade()
            ->deleteSspModelCollection($sspModelCollectionDeleteCriteriaTransfer);

        // Assert
        $this->assertCount(0, $sspModelCollectionResponseTransfer->getSspModels());

        $remainingSspModelEntities = $this->tester->getSspModelEntities();
        $this->assertCount(1, $remainingSspModelEntities);
        $this->assertSame(
            $sspModelTransfer->getIdSspModel(),
            $remainingSspModelEntities[0]->getIdSspModel(),
        );
    }
}
