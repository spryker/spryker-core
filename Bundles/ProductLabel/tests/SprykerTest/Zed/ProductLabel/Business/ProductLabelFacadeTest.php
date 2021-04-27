<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabel\Business;

use Codeception\Test\Unit;
use DateInterval;
use DateTime;
use Generated\Shared\DataBuilder\ProductLabelBuilder;
use Generated\Shared\DataBuilder\ProductLabelCriteriaBuilder;
use Generated\Shared\DataBuilder\ProductLabelLocalizedAttributesBuilder;
use Generated\Shared\DataBuilder\ProductLabelProductAbstractRelationsBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelStoreQuery;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;
use Spryker\Zed\ProductLabel\Dependency\Plugin\ProductLabelRelationUpdaterPluginInterface;
use Spryker\Zed\ProductLabel\ProductLabelDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabel
 * @group Business
 * @group Facade
 * @group ProductLabelFacadeTest
 * Add your own group annotations below this line
 */
class ProductLabelFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductLabel\ProductLabelBusinessTester
     */
    protected $tester;

    public const STORE_NAME_DE = 'DE';
    public const STORE_NAME_AT = 'AT';

    /**
     * @return void
     */
    public function testFindLabelByIdShouldReturnProductLabelTransfer(): void
    {
        $productLabelTransfer = $this->tester->haveProductLabel();

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertInstanceOf('\Generated\Shared\Transfer\ProductLabelTransfer', $productLabelTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveLabelShouldRemoveProductLabel(): void
    {
        //Arrange
        $productLabelTransfer = $this->tester->haveProductLabel();

        //Act
        $productLabelResponseTransfer = $this->getProductLabelFacade()->removeLabel($productLabelTransfer);

        //Assert
        $this->assertTrue($productLabelResponseTransfer->getIsSuccessful(), 'Response transfer does not match the expected result');

        $deletedProductLabel = SpyProductLabelQuery::create()
            ->filterByIdProductLabel($productLabelTransfer->getIdProductLabel())
            ->findOne();

        $this->assertNull($deletedProductLabel, 'Product label record was not deleted');
    }

    /**
     * @return void
     */
    public function testGetActiveLabelsByCriteriaShouldRetrieveActiveLabels(): void
    {
        //Arrange
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAT = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $storeRelationSeedData = [
            StoreRelationTransfer::ID_STORES => [
                $storeTransferDE->getIdStore(),
                $storeTransferAT->getIdStore(),
            ],
        ];
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        $productLabelCriteria = (new ProductLabelCriteriaBuilder())->seed([
            ProductLabelCriteriaTransfer::PRODUCT_ABSTRACT_IDS => [$idProductAbstract],
            ProductLabelCriteriaTransfer::PRODUCT_LABEL_IDS => [$idProductLabel],
            ProductLabelCriteriaTransfer::STORE_NAME => $storeTransferDE->getName(),
        ])->build();

        //Act
        $productLabelFacade = $this->getProductLabelFacade();
        $activeProductLabelsByTheGivenCriteria = $productLabelFacade->getActiveLabelsByCriteria($productLabelCriteria);

        //Assert
        $this->assertCount(
            1,
            $activeProductLabelsByTheGivenCriteria,
            'Product label not found.'
        );

        $this->assertSame(
            $idProductLabel,
            $activeProductLabelsByTheGivenCriteria[0]->getIdProductLabel(),
            'Wrong product label.'
        );

        $this->assertCount(
            2,
            $activeProductLabelsByTheGivenCriteria[0]->getStoreRelation()->getStores(),
            'Stores relations number is incorrect.'
        );

        $this->assertCount(
            $productLabelTransfer->getLocalizedAttributesCollection()->count(),
            $activeProductLabelsByTheGivenCriteria[0]->getLocalizedAttributesCollection(),
            'Localized attributes number is incorrect.'
        );
    }

    /**
     * @return void
     */
    public function testFindLabelByIdShouldReturnNullIfLabelDoesNotExist(): void
    {
        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelTransfer = $productLabelFacade->findLabelById(666);

        $this->assertNull($productLabelTransfer);
    }

    /**
     * @return void
     */
    public function testFindLabelByIdShouldReturnCollectionOfLocalizedAttributes(): void
    {
        $localeTransfer = $this->tester->haveLocale();

        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelTransfer->addLocalizedAttributes(
            $this->generateLocalizedAttributesTransfer($localeTransfer->getIdLocale())
        );
        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->createLabel($productLabelTransfer);

        $persistedProductLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertSame(1, $persistedProductLabelTransfer->getLocalizedAttributesCollection()->count());
    }

    /**
     * @return void
     */
    public function testFindAllLabelsShouldReturnCollectionSortedByPosition(): void
    {
        $this->tester->haveProductLabel([ProductLabelTransfer::POSITION => 100]);
        $this->tester->haveProductLabel([ProductLabelTransfer::POSITION => 101]);
        $this->tester->haveProductLabel([ProductLabelTransfer::POSITION => 102]);
        $this->tester->haveProductLabel([ProductLabelTransfer::POSITION => 103]);

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelTransferCollection = $productLabelFacade->findAllLabels();

        $this->assertSame(103, array_pop($productLabelTransferCollection)->getPosition());
        $this->assertSame(102, array_pop($productLabelTransferCollection)->getPosition());
        $this->assertSame(101, array_pop($productLabelTransferCollection)->getPosition());
        $this->assertSame(100, array_pop($productLabelTransferCollection)->getPosition());
    }

    /**
     * @return void
     */
    public function testCreateLabelShouldPersistDataAndUpdatesTransferIdField(): void
    {
        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelFacade->createLabel($productLabelTransfer);

        $persistedProductLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertNotNull($productLabelTransfer->getIdProductLabel());
        $this->assertSame($productLabelTransfer->getIdProductLabel(), $persistedProductLabelTransfer->getIdProductLabel());
    }

    /**
     * @return void
     */
    public function testUpdateLabelShouldPersistChanges(): void
    {
        $productLabelTransfer = $this->tester->haveProductLabel([
            'idActive' => true,
            'isExclusive' => false,
        ]);

        $productLabelTransfer->setIsActive(false);
        $productLabelTransfer->setIsExclusive(true);
        $productLabelTransfer->setName('FooBarBaz');

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->updateLabel($productLabelTransfer);

        $updatedProductLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertFalse($updatedProductLabelTransfer->getIsActive());
        $this->assertTrue($updatedProductLabelTransfer->getIsExclusive());
        $this->assertSame('FooBarBaz', $updatedProductLabelTransfer->getName());
    }

    /**
     * @return void
     */
    public function testCreateLabelShouldPersistLocalizedAttributes(): void
    {
        $localeTransfer = $this->tester->haveLocale();

        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelTransfer->addLocalizedAttributes(
            $this->generateLocalizedAttributesTransfer($localeTransfer->getIdLocale())
        );
        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->createLabel($productLabelTransfer);

        $persistedProductLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertSame(1, $persistedProductLabelTransfer->getLocalizedAttributesCollection()->count());

        /** @var \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[] $localizedAttributesList */
        $localizedAttributesList = $persistedProductLabelTransfer->getLocalizedAttributesCollection()->getArrayCopy();
        $this->assertSame($productLabelTransfer->getIdProductLabel(), $localizedAttributesList[0]->getFkProductLabel());
        $this->assertSame($localeTransfer->getIdLocale(), $localizedAttributesList[0]->getFkLocale());
    }

    /**
     * @return void
     */
    public function testCreateLabelShouldPersistStoreRelations(): void
    {
        //Arrange
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAT = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $storeRelationSeedData = [
            StoreRelationTransfer::ID_STORES => [
                $storeTransferDE->getIdStore(),
                $storeTransferAT->getIdStore(),
            ],
        ];
        $productLabelTransfer = (new ProductLabelBuilder([
            ProductLabelTransfer::ID_PRODUCT_LABEL => null,
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]))->build();

        //Act
        $this->getProductLabelFacade()->createLabel($productLabelTransfer);

        //Assert
        $productLabelEntity = SpyProductLabelQuery::create()
            ->filterByName($productLabelTransfer->getName())
            ->findOne();

        $productLabelStoreRelationExists = SpyProductLabelStoreQuery::create()
            ->filterByFkProductLabel($productLabelEntity->getIdProductLabel())
            ->exists();
        $this->assertTrue($productLabelStoreRelationExists, 'Relation between store and product label should exists');
    }

    /**
     * @return void
     */
    public function testUpdateLabelShouldUpdateStoreRelations(): void
    {
        //Arrange
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAT = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $storeRelationSeedDataForDE = [
            StoreRelationTransfer::ID_STORES => [
                $storeTransferDE->getIdStore(),
            ],
        ];
        $storeRelationSeedDataForAT = [
            StoreRelationTransfer::ID_STORES => [
                $storeTransferAT->getIdStore(),
            ],
        ];

        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedDataForDE,
        ]);

        $storeRelationTransfer = (new StoreRelationBuilder())->seed($storeRelationSeedDataForAT)->build();

        //Act
        $productLabelTransfer->setStoreRelation($storeRelationTransfer);
        $this->getProductLabelFacade()->updateLabel($productLabelTransfer);

        //Assert
        $productLabelStoreRelationAfterUpdate = SpyProductLabelStoreQuery::create()
            ->filterByFkProductLabel($productLabelTransfer->getIdProductLabel())
            ->find();

        $this->assertCount(
            1,
            $productLabelStoreRelationAfterUpdate,
            'Product label store relation data should be relevant'
        );
        $this->assertSame(
            $storeTransferAT->getIdStore(),
            $productLabelStoreRelationAfterUpdate->offsetGet(0)->getFkStore(),
            'Product label store relation'
        );
    }

    /**
     * @return void
     */
    public function testFindLabelByIdShouldReturnProductLabelTransferForSpecificStore(): void
    {
        //Arrange
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => 'test1',
            ProductLabelTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_STORES => [
                    $storeTransferDE->getIdStore(),
                ],
            ],
        ]);

        //Act
        $productLabelTransfer = $this->getProductLabelFacade()->findLabelById($productLabelTransfer->getIdProductLabel());

        //Assert
        $this->tester->assertEquals(
            $storeTransferDE->getName(),
            $productLabelTransfer->getStoreRelation()->getStores()->offsetGet(0)->getName(),
            'Given store is invalid for this relation'
        );
    }

    /**
     * @return void
     */
    public function testFindLabelByLabelNameShouldReturnProductLabelTransferForSpecificStore(): void
    {
        //Arrange
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => 'test1',
            ProductLabelTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_STORES => [
                    $storeTransferDE->getIdStore(),
                ],
            ],
        ]);

        $productLabelFacade = $this->getProductLabelFacade();
        //Act
        $productLabelTransfer = $productLabelFacade->findLabelByLabelName($productLabelTransfer->getName());

        //Assert
        $this->tester->assertEquals(
            $storeTransferDE->getName(),
            $productLabelTransfer->getStoreRelation()->getStores()->offsetGet(0)->getName(),
            'Given store is invalid for this relation'
        );
    }

    /**
     * @return void
     */
    public function testCreateLabelShouldTouchDictionaryActive(): void
    {
        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->createLabel($productLabelTransfer);

        $this->tester->assertTouchActive(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER
        );
    }

    /**
     * @return void
     */
    public function testFindProductAbstractRelationsByIdProductLabelShouldReturnListOfIdsProductAbstract(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        $productLabelFacade = $this->getProductLabelFacade();
        $idsProductAbstract = $productLabelFacade->findProductAbstractRelationsByIdProductLabel($idProductLabel);

        $this->assertCount(1, $idsProductAbstract);
        $this->assertSame($idProductAbstract, $idsProductAbstract[0]);
    }

    /**
     * @return void
     */
    public function testAddProductAbstractRelationsShouldPersistRelations(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->addAbstractProductRelationsForLabel($idProductLabel, [$idProductAbstract]);

        $productLabelTransferCollection = $productLabelFacade->findLabelsByIdProductAbstract($idProductAbstract);

        $this->assertCount(1, $productLabelTransferCollection);
    }

    /**
     * @return void
     */
    public function testFindProductLabelIdsByIdProductAbstractShouldReturnListOfIds(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelIds = $productLabelFacade->findLabelIdsByIdProductAbstract($idProductAbstract);

        $this->assertEquals([$idProductLabel], $productLabelIds);
    }

    /**
     * @return void
     */
    public function testRemoveProductAbstractRelationsShouldRemoveExistingRelations(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->removeProductAbstractRelationsForLabel($idProductLabel, [$idProductAbstract]);

        $idsProductAbstract = $productLabelFacade->findProductAbstractRelationsByIdProductLabel($idProductLabel);

        $this->assertCount(0, $idsProductAbstract);
    }

    /**
     * @return void
     */
    public function testAddProductAbstractRelationsShouldTouchRelationsActive(): void
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->addAbstractProductRelationsForLabel($idProductLabel, [$idProductAbstract]);

        $this->tester->assertTouchActive(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $idProductAbstract
        );

        $this->tester->assertTouchActive(
            ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT,
            $idProductAbstract
        );
    }

    /**
     * @return void
     */
    public function testCheckLabelValidityDateRangeAndTouchTouchesUnpublishedLabelsActiveWhenEnteringValidityDateRange(): void
    {
        $this->tester->haveProductLabel([
            'validFrom' => (new DateTime())->setTimestamp(strtotime('-1 day'))->format('Y-m-d'),
            'validTo' => (new DateTime())->setTimestamp(strtotime('+1 day'))->format('Y-m-d'),
            'isPublished' => false,
        ]);

        $referenceTime = new DateTime('now');
        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->checkLabelValidityDateRangeAndTouch();

        $this->tester->assertTouchActiveAfter(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
            $referenceTime
        );
    }

    /**
     * @return void
     */
    public function testCheckLabelValidityDateRangeAndTouchTouchesPublishedLabelsDeletedWhenLeavingValidityDateRange(): void
    {
        $this->tester->haveProductLabel([
            'validFrom' => (new DateTime())->setTimestamp(strtotime('-3 day'))->format('Y-m-d'),
            'validTo' => (new DateTime())->setTimestamp(strtotime('-1 day'))->format('Y-m-d'),
            'isPublished' => true,
        ]);

        $referenceTime = new DateTime('now');
        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->checkLabelValidityDateRangeAndTouch();

        $this->tester->assertTouchActiveAfter(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
            $referenceTime
        );
    }

    /**
     * @return void
     */
    public function testCheckLabelValidityDateRangeAndTouchDoesNotTouchActiveWhenAlreadyPublished(): void
    {
        $this->tester->haveProductLabel([
            'validFrom' => (new DateTime())->setTimestamp(strtotime('-2 day'))->format('Y-m-d'),
            'validTo' => (new DateTime())->setTimestamp(strtotime('+1 day'))->format('Y-m-d'),
            'isPublished' => true,
        ]);
        $referenceTime = new DateTime();
        $referenceTime->add(new DateInterval('PT1S'));

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->checkLabelValidityDateRangeAndTouch();

        $this->tester->assertNoTouchAfter(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
            $referenceTime
        );
    }

    /**
     * @return void
     */
    public function testCheckLabelValidityDateRangeAndTouchDoesNotTouchDeletedWhenAlreadyUnpublished(): void
    {
        $this->tester->haveProductLabel([
            'validFrom' => (new DateTime())->setTimestamp(strtotime('-3 day'))->format('Y-m-d'),
            'validTo' => (new DateTime())->setTimestamp(strtotime('-1 day'))->format('Y-m-d'),
            'isPublished' => false,
        ]);
        $referenceTime = new DateTime();
        $referenceTime->add(new DateInterval('PT1S'));

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->checkLabelValidityDateRangeAndTouch();

        $this->tester->assertNoTouchAfter(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
            $referenceTime
        );
    }

    /**
     * @return void
     */
    public function testUpdateDynamicProductLabelRelationsPersistRelationChanges(): void
    {
        // Arrange
        $productTransfer1 = $this->tester->haveProduct();
        $productTransfer2 = $this->tester->haveProduct();
        $productTransfer3 = $this->tester->haveProduct();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productTransfer2->getFkProductAbstract(),
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productTransfer3->getFkProductAbstract(),
        );

        $productLabelRelationUpdaterPluginMock = $this->getMockBuilder(ProductLabelRelationUpdaterPluginInterface::class)
            ->setMethods(['findProductLabelProductAbstractRelationChanges'])
            ->getMock();

        $productLabelRelationUpdaterPluginMock->method('findProductLabelProductAbstractRelationChanges')->willReturn([
            (new ProductLabelProductAbstractRelationsBuilder([
                ProductLabelProductAbstractRelationsTransfer::ID_PRODUCT_LABEL => $productLabelTransfer->getIdProductLabel(),
                ProductLabelProductAbstractRelationsTransfer::IDS_PRODUCT_ABSTRACT_TO_ASSIGN => [
                    $productTransfer1->getFkProductAbstract(),
                ],
                ProductLabelProductAbstractRelationsTransfer::IDS_PRODUCT_ABSTRACT_TO_DE_ASSIGN => [
                    $productTransfer2->getFkProductAbstract(),
                    $productTransfer3->getFkProductAbstract(),
                ],
            ]))->build(),
        ]);

        $this->tester->setDependency(ProductLabelDependencyProvider::PLUGIN_PRODUCT_LABEL_RELATION_UPDATERS, [
            $productLabelRelationUpdaterPluginMock,
        ]);

        $this->tester->setConfig(ProductLabelConstants::PRODUCT_LABEL_TO_DE_ASSIGN_CHUNK_SIZE, 1);

        // Act
        $this->getProductLabelFacade()->updateDynamicProductLabelRelations();

        // Assert
        $actualIdProductAbstracts = $this
            ->getProductLabelFacade()
            ->findProductAbstractRelationsByIdProductLabel($productLabelTransfer->getIdProductLabel());

        $this->assertEquals([$productTransfer1->getFkProductAbstract()], $actualIdProductAbstracts);

        $this->tester->assertTouchActive(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $productTransfer1->getFkProductAbstract()
        );
        $this->tester->assertTouchActive(
            ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT,
            $productTransfer1->getFkProductAbstract()
        );

        $this->tester->assertTouchDeleted(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $productTransfer2->getFkProductAbstract()
        );
        $this->tester->assertTouchDeleted(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $productTransfer3->getFkProductAbstract()
        );
        $this->tester->assertTouchActive(
            ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT,
            $productTransfer1->getFkProductAbstract()
        );
    }

    /**
     * @return void
     */
    public function testGetProductLabelProductAbstractsByProductAbstractIdsReturnsCorrectData(): void
    {
        // Arrange
        $productTransfer1 = $this->tester->haveProduct();
        $productLabelTransfer1 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer1->getIdProductLabel(),
            $productTransfer1->getFkProductAbstract()
        );

        $productTransfer2 = $this->tester->haveProduct();
        $productLabelTransfer2 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productTransfer2->getFkProductAbstract()
        );

        $productAbstractIds = [
            $productTransfer1->getFkProductAbstract(),
            $productTransfer2->getFkProductAbstract(),
        ];

        // Act
        $productLabelProductAbstracts = $this->getProductLabelFacade()
            ->getProductLabelProductAbstractsByProductAbstractIds($productAbstractIds);

        // Assert
        $this->assertCount(count($productAbstractIds), $productLabelProductAbstracts);
    }

    /**
     * @return void
     */
    public function testGetProductLabelProductAbstractsByProductAbstractIdsReturnsEmptyCollectionIfDataIsNotCorrect(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productTransfer->getFkProductAbstract()
        );

        $productAbstractIds = [
            $productTransfer->getFkProductAbstract() + 1,
        ];

        // Act
        $productLabelProductAbstracts = $this->getProductLabelFacade()
            ->getProductLabelProductAbstractsByProductAbstractIds($productAbstractIds);

        // Assert
        $this->assertCount(0, $productLabelProductAbstracts);
    }

    /**
     * @return void
     */
    public function testGetProductLabelProductAbstractsByFilterReturnsCorrectData(): void
    {
        // Arrange
        $productTransfer1 = $this->tester->haveProduct();
        $productLabelTransfer1 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer1->getIdProductLabel(),
            $productTransfer1->getFkProductAbstract()
        );

        $productTransfer2 = $this->tester->haveProduct();
        $productLabelTransfer2 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productTransfer2->getFkProductAbstract()
        );

        $filterTransfer = (new FilterTransfer())
            ->setLimit(1)
            ->setOffset(0);

        // Act
        $productLabelProductAbstracts = $this->getProductLabelFacade()
            ->getProductLabelProductAbstractsByFilter($filterTransfer);

        // Assert
        $this->assertCount(1, $productLabelProductAbstracts);
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected function getProductLabelFacade(): ProductLabelFacadeInterface
    {
        return $this->tester->getLocator()->productLabel()->facade();
    }

    /**
     * @param int|null $fkLocale
     * @param int|null $fkProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer
     */
    protected function generateLocalizedAttributesTransfer(?int $fkLocale = null, ?int $fkProductLabel = null): ProductLabelLocalizedAttributesTransfer
    {
        $builder = new ProductLabelLocalizedAttributesBuilder([
            'fkProductLabel' => $fkProductLabel,
            'fkLocale' => $fkLocale,
        ]);

        return $builder->build();
    }
}
