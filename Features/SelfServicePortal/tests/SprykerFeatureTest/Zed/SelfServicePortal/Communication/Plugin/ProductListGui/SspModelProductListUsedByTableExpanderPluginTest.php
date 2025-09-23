<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductListGui;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableTransfer;
use Generated\Shared\Transfer\SspModelCollectionTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductListGui\SspModelProductListUsedByTableExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\TableDataProvider\SspModelProductListUsedByTableExpander;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductListGui
 * @group SspModelProductListUsedByTableExpanderPluginTest
 */
class SspModelProductListUsedByTableExpanderPluginTest extends Unit
{
    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureSspAssetRelatedTablesAreEmpty();
    }

    public function testExpandAddsRelatedSspModelsToUsedByTable(): void
    {
        // Arrange
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TYPE => 'whitelist',
            ProductListTransfer::TITLE => 'Test Product List',
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Model 1',
            SspModelTransfer::CODE => 'MODEL001',
            SspModelTransfer::PRODUCT_LISTS => [$productListTransfer],
        ]);

        $sspModelTransfer2 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Model 2',
            SspModelTransfer::CODE => 'MODEL002',
            SspModelTransfer::PRODUCT_LISTS => [$productListTransfer],
        ]);

        $productListUsedByTableTransfer = (new ProductListUsedByTableTransfer())
            ->setProductList($productListTransfer);

        $repositoryMock = $this->createMock(SelfServicePortalRepositoryInterface::class);
        $repositoryMock->method('getSspModelIdsByProductListId')
            ->with($productListTransfer->getIdProductList())
            ->willReturn([$sspModelTransfer1->getIdSspModel(), $sspModelTransfer2->getIdSspModel()]);

        $repositoryMock->method('getSspModelCollection')
            ->willReturnCallback(function ($criteria) use ($sspModelTransfer1, $sspModelTransfer2) {
                $collection = new SspModelCollectionTransfer();
                $collection->addSspModel($sspModelTransfer1);
                $collection->addSspModel($sspModelTransfer2);

                return $collection;
            });

        $tableExpander = new SspModelProductListUsedByTableExpander($repositoryMock);

        // Act
        $result = $tableExpander->expandTableData($productListUsedByTableTransfer);

        // Assert
        $this->assertInstanceOf(ProductListUsedByTableTransfer::class, $result);
        $this->assertSame($productListTransfer, $result->getProductList());
        $this->assertCount(2, $result->getRows());

        $rows = $result->getRows()->getArrayCopy();

        $firstRow = $rows[0];
        $this->assertSame('Model', $firstRow->getTitle());
        $this->assertSame('Model 1', $firstRow->getName());
        $this->assertNotNull($firstRow->getActionButtons());

        $firstRowButtons = $firstRow->getActionButtons()->getButtons()->getArrayCopy();
        $this->assertCount(1, $firstRowButtons);
        $this->assertSame('Edit Model', $firstRowButtons[0]->getTitle());
        $this->assertSame(
            sprintf('/self-service-portal/update-model?id-ssp-model=%d', $sspModelTransfer1->getIdSspModel()),
            $firstRowButtons[0]->getUrl(),
        );
        $this->assertSame(['class' => 'btn-edit'], $firstRowButtons[0]->getDefaultOptions());

        $secondRow = $rows[1];
        $this->assertSame('Model', $secondRow->getTitle());
        $this->assertSame('Model 2', $secondRow->getName());
        $this->assertNotNull($secondRow->getActionButtons());

        $secondRowButtons = $secondRow->getActionButtons()->getButtons()->getArrayCopy();
        $this->assertCount(1, $secondRowButtons);
        $this->assertSame('Edit Model', $secondRowButtons[0]->getTitle());
        $this->assertSame(
            sprintf('/self-service-portal/update-model?id-ssp-model=%d', $sspModelTransfer2->getIdSspModel()),
            $secondRowButtons[0]->getUrl(),
        );
        $this->assertSame(['class' => 'btn-edit'], $secondRowButtons[0]->getDefaultOptions());
    }

    public function testExpandWithNoRelatedModelsReturnsOriginalTable(): void
    {
        // Arrange
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TYPE => 'whitelist',
            ProductListTransfer::TITLE => 'Test Product List Without Models',
        ]);

        $productListUsedByTableTransfer = (new ProductListUsedByTableTransfer())
            ->setProductList($productListTransfer);

        $repositoryMock = $this->createMock(SelfServicePortalRepositoryInterface::class);
        $repositoryMock->method('getSspModelIdsByProductListId')
            ->with($productListTransfer->getIdProductList())
            ->willReturn([]); // No model IDs

        $tableExpander = new SspModelProductListUsedByTableExpander($repositoryMock);

        // Act
        $result = $tableExpander->expandTableData($productListUsedByTableTransfer);

        // Assert
        $this->assertInstanceOf(ProductListUsedByTableTransfer::class, $result);
        $this->assertSame($productListTransfer, $result->getProductList());
        $this->assertCount(0, $result->getRows());
    }

    public function testExpandWithMultipleProductListsOnlyAddsModelsForCorrectProductList(): void
    {
        // Arrange
        $productListTransfer1 = $this->tester->haveProductList([
            ProductListTransfer::TYPE => 'whitelist',
            ProductListTransfer::TITLE => 'Product List 1',
        ]);

        $productListTransfer2 = $this->tester->haveProductList([
            ProductListTransfer::TYPE => 'blacklist',
            ProductListTransfer::TITLE => 'Product List 2',
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Model for List 1',
            SspModelTransfer::CODE => 'MODEL_LIST1',
            SspModelTransfer::PRODUCT_LISTS => [$productListTransfer1],
        ]);

        $sspModelTransfer2 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Model for List 2',
            SspModelTransfer::CODE => 'MODEL_LIST2',
            SspModelTransfer::PRODUCT_LISTS => [$productListTransfer2],
        ]);

        $productListUsedByTableTransfer = (new ProductListUsedByTableTransfer())
            ->setProductList($productListTransfer1);

        $repositoryMock = $this->createMock(SelfServicePortalRepositoryInterface::class);
        $repositoryMock->method('getSspModelIdsByProductListId')
            ->with($productListTransfer1->getIdProductList())
            ->willReturn([$sspModelTransfer1->getIdSspModel()]);

        $repositoryMock->method('getSspModelCollection')
            ->willReturnCallback(function ($criteria) use ($sspModelTransfer1) {
                $collection = new SspModelCollectionTransfer();
                $collection->addSspModel($sspModelTransfer1);

                return $collection;
            });

        $tableExpander = new SspModelProductListUsedByTableExpander($repositoryMock);

        // Act
        $result = $tableExpander->expandTableData($productListUsedByTableTransfer);

        // Assert
        $this->assertInstanceOf(ProductListUsedByTableTransfer::class, $result);
        $this->assertSame($productListTransfer1, $result->getProductList());
        $this->assertCount(1, $result->getRows());

        $rows = $result->getRows()->getArrayCopy();
        $row = $rows[0];

        $this->assertSame('Model', $row->getTitle());
        $this->assertSame('Model for List 1', $row->getName());
        $this->assertSame(
            sprintf('/self-service-portal/update-model?id-ssp-model=%d', $sspModelTransfer1->getIdSspModel()),
            $row->getActionButtons()->getButtons()->getArrayCopy()[0]->getUrl(),
        );
    }

    public function testExpandPreservesExistingRowsInTable(): void
    {
        // Arrange
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TYPE => 'whitelist',
            ProductListTransfer::TITLE => 'Test Product List',
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Test Model',
            SspModelTransfer::CODE => 'TEST_MODEL',
            SspModelTransfer::PRODUCT_LISTS => [$productListTransfer],
        ]);

        $existingRow = new ProductListUsedByTableRowTransfer();
        $existingRow->setTitle('Existing Type')
            ->setName('Existing Item')
            ->setActionButtons(new ButtonCollectionTransfer());

        $productListUsedByTableTransfer = (new ProductListUsedByTableTransfer())
            ->setProductList($productListTransfer)
            ->addRow($existingRow);

        $repositoryMock = $this->createMock(SelfServicePortalRepositoryInterface::class);
        $repositoryMock->method('getSspModelIdsByProductListId')
            ->with($productListTransfer->getIdProductList())
            ->willReturn([$sspModelTransfer->getIdSspModel()]);

        $repositoryMock->method('getSspModelCollection')
            ->willReturnCallback(function ($criteria) use ($sspModelTransfer) {
                $collection = new SspModelCollectionTransfer();
                $collection->addSspModel($sspModelTransfer);

                return $collection;
            });

        $tableExpander = new SspModelProductListUsedByTableExpander($repositoryMock);

        // Act
        $result = $tableExpander->expandTableData($productListUsedByTableTransfer);

        // Assert
        $this->assertInstanceOf(ProductListUsedByTableTransfer::class, $result);
        $this->assertSame($productListTransfer, $result->getProductList());
        $this->assertCount(2, $result->getRows()); // 1 existing + 1 new SSP model

        $rows = $result->getRows()->getArrayCopy();

        $this->assertSame('Existing Type', $rows[0]->getTitle());
        $this->assertSame('Existing Item', $rows[0]->getName());

        $this->assertSame('Model', $rows[1]->getTitle());
        $this->assertSame('Test Model', $rows[1]->getName());
    }

    public function testExpandHandlesModelWithoutNameGracefully(): void
    {
        // Arrange
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TYPE => 'whitelist',
            ProductListTransfer::TITLE => 'Test Product List',
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => '', // Empty name
            SspModelTransfer::CODE => 'EMPTY_NAME_MODEL',
            SspModelTransfer::PRODUCT_LISTS => [$productListTransfer],
        ]);

        $productListUsedByTableTransfer = (new ProductListUsedByTableTransfer())
            ->setProductList($productListTransfer);

        $repositoryMock = $this->createMock(SelfServicePortalRepositoryInterface::class);
        $repositoryMock->method('getSspModelIdsByProductListId')
            ->with($productListTransfer->getIdProductList())
            ->willReturn([$sspModelTransfer->getIdSspModel()]);

        $repositoryMock->method('getSspModelCollection')
            ->willReturnCallback(function ($criteria) use ($sspModelTransfer) {
                $collection = new SspModelCollectionTransfer();
                $collection->addSspModel($sspModelTransfer);

                return $collection;
            });

        $tableExpander = new SspModelProductListUsedByTableExpander($repositoryMock);

        // Act
        $result = $tableExpander->expandTableData($productListUsedByTableTransfer);

        // Assert
        $this->assertInstanceOf(ProductListUsedByTableTransfer::class, $result);
        $this->assertCount(1, $result->getRows());

        $rows = $result->getRows()->getArrayCopy();
        $row = $rows[0];

        $this->assertSame('Model', $row->getTitle());
        $this->assertSame('', $row->getName()); // Empty name should be preserved
    }

    public function testPluginIntegrationWithMockedFactory(): void
    {
        // Arrange
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TYPE => 'whitelist',
            ProductListTransfer::TITLE => 'Integration Test Product List',
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Integration Test Model',
            SspModelTransfer::CODE => 'INTEGRATION_MODEL',
        ]);

        $productListUsedByTableTransfer = (new ProductListUsedByTableTransfer())
            ->setProductList($productListTransfer);

        $tableExpanderMock = $this->createMock(SspModelProductListUsedByTableExpander::class);
        $tableExpanderMock->expects($this->once())
            ->method('expandTableData')
            ->with($productListUsedByTableTransfer)
            ->willReturn($productListUsedByTableTransfer->addRow(
                (new ProductListUsedByTableRowTransfer())
                    ->setTitle('Model')
                    ->setName('Integration Test Model')
                    ->setActionButtons(new ButtonCollectionTransfer()),
            ));

        $factoryMock = $this->createMock(SelfServicePortalCommunicationFactory::class);
        $factoryMock->expects($this->once())
            ->method('createSspModelProductListUsedByTableExpander')
            ->willReturn($tableExpanderMock);

        // Create plugin with mocked factory
        $plugin = new SspModelProductListUsedByTableExpanderPlugin();
        $plugin->setFactory($factoryMock);

        // Act
        $result = $plugin->expand($productListUsedByTableTransfer);

        // Assert
        $this->assertInstanceOf(ProductListUsedByTableTransfer::class, $result);
        $this->assertCount(1, $result->getRows());

        $row = $result->getRows()->getArrayCopy()[0];
        $this->assertSame('Model', $row->getTitle());
        $this->assertSame('Integration Test Model', $row->getName());
    }
}
