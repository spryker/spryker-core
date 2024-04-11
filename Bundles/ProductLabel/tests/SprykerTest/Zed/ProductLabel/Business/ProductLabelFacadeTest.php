<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabel\Business;

use ArrayObject;
use Codeception\Test\Unit;
use DateInterval;
use DateTime;
use Generated\Shared\DataBuilder\ProductLabelBuilder;
use Generated\Shared\DataBuilder\ProductLabelCriteriaBuilder;
use Generated\Shared\DataBuilder\ProductLabelLocalizedAttributesBuilder;
use Generated\Shared\DataBuilder\ProductLabelProductAbstractRelationsBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductLabelConditionsTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelStoreQuery;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToEventInterface;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToProductInterface;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface;
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
     * @uses \Spryker\Zed\Product\Dependency\ProductEvents::PRODUCT_ABSTRACT_UPDATE
     *
     * @var string
     */
    protected const PRODUCT_ABSTRACT_UPDATE = 'Product.product_abstract.update';

    /**
     * @var \SprykerTest\Zed\ProductLabel\ProductLabelBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    public const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    public const STORE_NAME_AT = 'AT';

    /**
     * @var \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToEventInterface
     */
    protected $eventFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->eventFacade = $this->createMock(ProductLabelToEventInterface::class);

        $this->tester->mockFactoryMethod(
            'getEventFacade',
            $this->eventFacade,
        );
    }

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
            'Product label not found.',
        );

        $this->assertSame(
            $idProductLabel,
            $activeProductLabelsByTheGivenCriteria[0]->getIdProductLabel(),
            'Wrong product label.',
        );

        $this->assertCount(
            2,
            $activeProductLabelsByTheGivenCriteria[0]->getStoreRelation()->getStores(),
            'Stores relations number is incorrect.',
        );

        $this->assertCount(
            $productLabelTransfer->getLocalizedAttributesCollection()->count(),
            $activeProductLabelsByTheGivenCriteria[0]->getLocalizedAttributesCollection(),
            'Localized attributes number is incorrect.',
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
            $this->generateLocalizedAttributesTransfer($localeTransfer->getIdLocale()),
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
            $this->generateLocalizedAttributesTransfer($localeTransfer->getIdLocale()),
        );
        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelFacade->createLabel($productLabelTransfer);

        $persistedProductLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertSame(1, $persistedProductLabelTransfer->getLocalizedAttributesCollection()->count());

        /** @var array<\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer> $localizedAttributesList */
        $localizedAttributesList = $persistedProductLabelTransfer->getLocalizedAttributesCollection()->getArrayCopy();
        $this->assertSame($productLabelTransfer->getIdProductLabel(), $localizedAttributesList[0]->getFkProductLabel());
        $this->assertSame($localeTransfer->getIdLocale(), $localizedAttributesList[0]->getFkLocale());
    }

    /**
     * @return void
     */
    public function testUpdateLabelShouldTouchDictionaryWhenLabelIsUpdated(): void
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_EXCLUSIVE => true,
        ]);

        $touchFacadeMock = $this->createTouchFacadeMock();
        $this->tester->setDependency(ProductLabelDependencyProvider::FACADE_TOUCH, $touchFacadeMock);

        $productLabelTransfer->setIsExclusive(false);

        // Assert
        $touchFacadeMock->expects($this->once())
            ->method('touchActive')
            ->with(
                ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
                ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
            );

        // Act
        $this->getProductLabelFacade()->updateLabel($productLabelTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateLabelShouldNotTouchDictionaryWhenLabelIsNotUpdated(): void
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_EXCLUSIVE => true,
        ]);

        $touchFacadeMock = $this->createTouchFacadeMock();
        $this->tester->setDependency(ProductLabelDependencyProvider::FACADE_TOUCH, $touchFacadeMock);

        // Assert
        $touchFacadeMock->expects($this->never())->method('touchActive');

        // Act
        $this->getProductLabelFacade()->updateLabel($productLabelTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateLabelShouldTouchProductRelationWhenIsActiveUpdated(): void
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_ACTIVE => true,
        ]);
        $idProductAbstract = $this->tester->haveProduct()->getFkProductAbstract();

        $this->tester->haveProductLabelToAbstractProductRelation($productLabelTransfer->getIdProductLabel(), $idProductAbstract);

        $touchFacadeMock = $this->createTouchFacadeMock();
        $this->tester->setDependency(ProductLabelDependencyProvider::FACADE_TOUCH, $touchFacadeMock);

        $productFacadeMock = $this->createProductFacadeMock();
        $this->tester->setDependency(ProductLabelDependencyProvider::FACADE_PRODUCT, $productFacadeMock);

        $productLabelTransfer->setIsActive(false);

        // Assert
        $touchFacadeMock->expects($this->exactly(2))
            ->method('touchActive')
            ->withConsecutive(
                [
                    ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
                    ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
                ],
                [
                    ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
                    $idProductAbstract,
                ],
            );

        $productFacadeMock->expects($this->once())
            ->method('touchProductAbstract')
            ->with($idProductAbstract);

        // Act
        $this->getProductLabelFacade()->updateLabel($productLabelTransfer);
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
            'Product label store relation data should be relevant',
        );
        $this->assertSame(
            $storeTransferAT->getIdStore(),
            $productLabelStoreRelationAfterUpdate->offsetGet(0)->getFkStore(),
            'Product label store relation',
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
            'Given store is invalid for this relation',
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
            'Given store is invalid for this relation',
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
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
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
            $idProductAbstract,
        );

        $this->tester->assertTouchActive(
            ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT,
            $idProductAbstract,
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
            $referenceTime,
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
            $referenceTime,
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
            $referenceTime,
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
            $referenceTime,
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
            $productTransfer1->getFkProductAbstract(),
        );
        $this->tester->assertTouchActive(
            ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT,
            $productTransfer1->getFkProductAbstract(),
        );

        $this->tester->assertTouchDeleted(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $productTransfer2->getFkProductAbstract(),
        );
        $this->tester->assertTouchDeleted(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $productTransfer3->getFkProductAbstract(),
        );
        $this->tester->assertTouchActive(
            ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT,
            $productTransfer1->getFkProductAbstract(),
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
            $productTransfer1->getFkProductAbstract(),
        );

        $productTransfer2 = $this->tester->haveProduct();
        $productLabelTransfer2 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productTransfer2->getFkProductAbstract(),
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
            $productTransfer->getFkProductAbstract(),
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
            $productTransfer1->getFkProductAbstract(),
        );

        $productTransfer2 = $this->tester->haveProduct();
        $productLabelTransfer2 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productTransfer2->getFkProductAbstract(),
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
     * @return void
     */
    public function testGetProductLabelCollectionReturnsCorrectProductLabelsWithoutPaginationAndRelations(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductLabelQuery::create());
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $productTransfer = $this->tester->haveProduct();
        $storeRelationSeedData = [
            StoreRelationTransfer::ID_STORES => [
                $storeTransferDE->getIdStore(),
            ],
        ];

        $productLabelTransfer1 = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer1->getIdProductLabel(),
            $productTransfer->getFkProductAbstract(),
        );
        $productLabelTransfer2 = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productTransfer->getFkProductAbstract(),
        );
        $productLabelTransfer3 = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer3->getIdProductLabel(),
            $productTransfer->getFkProductAbstract(),
        );

        $productLabelCriteriaTransfer = new ProductLabelCriteriaTransfer();

        // Act
        $productLabelCollectionTransfer = $this->tester->getFacade()->getProductLabelCollection($productLabelCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productLabelCollectionTransfer->getProductLabels());
        $this->assertSame(
            $productLabelTransfer1->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getIdProductLabel(),
        );
        $this->assertCount(0, $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getLocalizedAttributesCollection());
        $this->assertCount(0, $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getProductLabelProductAbstracts());
        $this->assertCount(0, $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getStoreRelation()->getStores());
        $this->assertSame(
            $productLabelTransfer2->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(1)->getIdProductLabel(),
        );
        $this->assertCount(0, $productLabelCollectionTransfer->getProductLabels()->offsetGet(1)->getLocalizedAttributesCollection());
        $this->assertCount(0, $productLabelCollectionTransfer->getProductLabels()->offsetGet(1)->getProductLabelProductAbstracts());
        $this->assertCount(0, $productLabelCollectionTransfer->getProductLabels()->offsetGet(1)->getStoreRelation()->getStores());
        $this->assertSame(
            $productLabelTransfer3->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(2)->getIdProductLabel(),
        );
        $this->assertCount(0, $productLabelCollectionTransfer->getProductLabels()->offsetGet(2)->getLocalizedAttributesCollection());
        $this->assertCount(0, $productLabelCollectionTransfer->getProductLabels()->offsetGet(2)->getProductLabelProductAbstracts());
        $this->assertCount(0, $productLabelCollectionTransfer->getProductLabels()->offsetGet(2)->getStoreRelation()->getStores());
    }

    /**
     * @return void
     */
    public function testGetProductLabelCollectionShouldReturnAllRelations(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductLabelQuery::create());
        $productTransfer = $this->tester->haveProduct();
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeRelationSeedData = [
            StoreRelationTransfer::ID_STORES => [
                $storeTransferDE->getIdStore(),
            ],
        ];
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productTransfer->getFkProductAbstract(),
        );

        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())
            ->setWithProductLabelLocalizedAttributes(true)
            ->setWithProductLabelProductAbstracts(true)
            ->setWithProductLabelStores(true);
        $localeTransfers = $this->tester->getLocator()->locale()->facade()->getLocaleCollection();

        // Act
        $productLabelCollectionTransfer = $this->tester->getFacade()->getProductLabelCollection($productLabelCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productLabelCollectionTransfer->getProductLabels());
        $this->assertSame(
            $productLabelTransfer->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getIdProductLabel(),
        );

        $this->assertCount(count($localeTransfers), $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getLocalizedAttributesCollection());

        $this->assertCount(1, $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getProductLabelProductAbstracts());
        $this->assertSame(
            $productTransfer->getFkProductAbstract(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getProductLabelProductAbstracts()->offsetGet(0)->getFkProductAbstract(),
        );

        $this->assertCount(1, $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getStoreRelation()->getStores());
        $this->assertSame(
            $storeTransferDE->getIdStore(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getStoreRelation()->getStores()->offsetGet(0)->getIdStore(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductLabelCollectionReturnsPaginatedProductLabelsWithLimitAndOffset(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductLabelQuery::create());
        $this->tester->haveProductLabel();
        $productLabelTransfer1 = $this->tester->haveProductLabel();
        $productLabelTransfer2 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabel();
        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setOffset(1)->setLimit(2),
            );

        // Act
        $productLabelCollectionTransfer = $this->tester->getFacade()->getProductLabelCollection($productLabelCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productLabelCollectionTransfer->getProductLabels());
        $this->assertSame(4, $productLabelCollectionTransfer->getPagination()->getNbResults());
        $this->assertSame(
            $productLabelTransfer1->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getIdProductLabel(),
        );
        $this->assertSame(
            $productLabelTransfer2->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(1)->getIdProductLabel(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductLabelCollectionShouldReturnCollectionFilteredByIdProductAbstract(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductLabelQuery::create());

        $idProductAbstract = $this->tester->haveProduct()->getFkProductAbstract();

        $productLabelTransfer = $this->tester->haveProductLabel();
        $this->tester->haveProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $idProductAbstract,
        );

        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())->setProductLabelConditions(
            (new ProductLabelConditionsTransfer())->addProductAbstractId($idProductAbstract),
        );

        // Act
        $productLabelCollectionTransfer = $this->tester->getFacade()->getProductLabelCollection($productLabelCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productLabelCollectionTransfer->getProductLabels());
        $this->assertSame(
            $productLabelTransfer->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->getIterator()->current()->getIdProductLabel(),
        );
    }

    /**
     * @return void
     */
    public function testProductConcretesSuccessfullyExpandedWithLabels(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productConcreteTransfer->getFkProductAbstract(),
        );

        $productConcreteTransfers = [$productConcreteTransfer];

        // Act
        $expandedProductConcreteTransfers = $this->tester->getFacade()->expandProductConcretesWithLabels($productConcreteTransfers);

        // Assert
        $this->assertSame(
            $expandedProductConcreteTransfers[0]->getProductLabels()->offsetGet(0)->getIdProductLabel(),
            $productLabelTransfer->getIdProductLabel(),
        );
    }

    /**
     * Tests if the repository do not set the wrong ProductLabelTransfer to a ProductLabelProductAbstractTransfer when
     * the product has more then one label assigned to it.
     *
     * This bug was reported on https://spryker.atlassian.net/browse/PBC-845.
     *
     * @return void
     */
    public function testExpandProductConcretesWithLabelsAssignCorrectLabelsToProductWhenProductHasMoreThanOneLabels(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();

        $productLabelTransfer1 = $this->tester->haveProductLabel();
        $idProductLabel1 = $productLabelTransfer1->getIdProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel1, $idProductAbstract);

        $productLabelTransfer2 = $this->tester->haveProductLabel();
        $idProductLabel2 = $productLabelTransfer2->getIdProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel2, $idProductAbstract);

        $productConcreteTransfers = [$productTransfer];

        // Act
        $expandedProductConcreteTransfers = $this->tester->getFacade()->expandProductConcretesWithLabels(
            $productConcreteTransfers,
        );

        $expandedLabelsids = [
            $expandedProductConcreteTransfers[0]->getProductLabels()->offsetGet(0)->getIdProductLabel(),
            $expandedProductConcreteTransfers[0]->getProductLabels()->offsetGet(1)->getIdProductLabel(),
        ];

        // Assert
        $this->assertContains($productLabelTransfer1->getIdProductLabel(), $expandedLabelsids);
        $this->assertContains($productLabelTransfer2->getIdProductLabel(), $expandedLabelsids);
    }

    /**
     * @return void
     */
    public function testGetProductLabelCollectionShouldReturnCollectionFilteredByIsActive(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductLabelQuery::create());

        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_ACTIVE => true,
        ]);
        $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_ACTIVE => false,
        ]);
        $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_ACTIVE => true,
            ProductLabelTransfer::VALID_TO => date('Y-m-d H:i:s', strtotime('-1 day')),
        ]);
        $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_ACTIVE => true,
            ProductLabelTransfer::VALID_FROM => date('Y-m-d H:i:s', strtotime('+1 day')),
        ]);

        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())->setProductLabelConditions(
            (new ProductLabelConditionsTransfer())->setIsActive(true),
        );

        // Act
        $productLabelCollectionTransfer = $this->tester->getFacade()->getProductLabelCollection($productLabelCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productLabelCollectionTransfer->getProductLabels());
        $this->assertSame(
            $productLabelTransfer->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->getIterator()->current()->getIdProductLabel(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductLabelCollectionShouldReturnCollectionSortedBySortCollection(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductLabelQuery::create());

        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_EXCLUSIVE => true,
            ProductLabelTransfer::POSITION => 3,
        ]);
        $productLabelTransfer2 = $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_EXCLUSIVE => false,
            ProductLabelTransfer::POSITION => 2,
        ]);
        $productLabelTransfer3 = $this->tester->haveProductLabel([
            ProductLabelTransfer::IS_EXCLUSIVE => false,
            ProductLabelTransfer::POSITION => 1,
        ]);

        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())->setSortCollection(new ArrayObject([
            (new SortTransfer())->setField(ProductLabelTransfer::IS_EXCLUSIVE)->setIsAscending(false),
            (new SortTransfer())->setField(ProductLabelTransfer::POSITION)->setIsAscending(true),
        ]));

        // Act
        $productLabelCollectionTransfer = $this->tester->getFacade()->getProductLabelCollection($productLabelCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productLabelCollectionTransfer->getProductLabels());
        $this->assertSame(
            $productLabelTransfer->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(0)->getIdProductLabel(),
        );
        $this->assertSame(
            $productLabelTransfer3->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(1)->getIdProductLabel(),
        );
        $this->assertSame(
            $productLabelTransfer2->getIdProductLabel(),
            $productLabelCollectionTransfer->getProductLabels()->offsetGet(2)->getIdProductLabel(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductLabelCollectionShouldReturnUniqueProductLabelsFilteredByProductAbstractIds(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductLabelQuery::create());

        $firstProductTransfer = $this->tester->haveProduct();
        $secondProductTransfer = $this->tester->haveProduct();

        $firstProductLabelTransfer = $this->tester->haveProductLabel();
        $secondProductLabelTransfer = $this->tester->haveProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation(
            $firstProductLabelTransfer->getIdProductLabel(),
            $firstProductTransfer->getFkProductAbstract(),
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $secondProductLabelTransfer->getIdProductLabel(),
            $firstProductTransfer->getFkProductAbstract(),
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $firstProductLabelTransfer->getIdProductLabel(),
            $secondProductTransfer->getFkProductAbstract(),
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $secondProductLabelTransfer->getIdProductLabel(),
            $secondProductTransfer->getFkProductAbstract(),
        );

        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())
            ->setProductLabelConditions(
                (new ProductLabelConditionsTransfer())
                    ->setIsActive(true)
                    ->addProductAbstractId($firstProductTransfer->getFkProductAbstract())
                    ->addProductAbstractId($secondProductTransfer->getFkProductAbstract()),
            );

        // Act
        $productLabelCollectionTransfer = $this->tester->getFacade()->getProductLabelCollection($productLabelCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productLabelCollectionTransfer->getProductLabels());
    }

    /**
     * @return void
     */
    public function testExpandProductConcretesWithLabelsReturnsEmptyArrayWhenProductAreEmpty(): void
    {
        // Arrange
        $productConcreteTransfers = [];

        // Act
        $expandedProductConcreteTransfers = $this->tester->getFacade()->expandProductConcretesWithLabels($productConcreteTransfers);

        // Assert
        $this->assertEmpty($expandedProductConcreteTransfers);
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

    /**
     * @return void
     */
    public function testAddProductAbstractRelationsEmitsProductEvents(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        // Assert
        $this->assertProductEventWithProductAbstractIdIsEmitted($idProductAbstract);

        // Act
        $this->tester->getFacade()->addAbstractProductRelationsForLabel($idProductLabel, [$idProductAbstract]);
    }

    /**
     * @return void
     */
    public function testUpdatingLabelEmitsProductEventsForRelatedProducts(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        // Assert
        $this->assertProductEventWithProductAbstractIdIsEmitted($idProductAbstract);

        // Act
        $this->tester->getFacade()->updateLabel($productLabelTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveAbstractRelationsEmitsProductEvent(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        // Assert
        $this->assertProductEventWithProductAbstractIdIsEmitted($idProductAbstract);

        // Act
        $this->tester->getFacade()->removeProductAbstractRelationsForLabel($idProductLabel, [$idProductAbstract]);
    }

    /**
     * @return void
     */
    public function testLabelRemovalEmitsProductEventForRelatedProducts(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        // Assert
        $this->assertProductEventWithProductAbstractIdIsEmitted($idProductAbstract);

        // Act
        $this->tester->getFacade()->removeLabel($productLabelTransfer);
    }

    /**
     * @return void
     */
    public function testTriggerProductAbstractUpdateEventsByProductLabelEventsTriggerEventsWithCorrectProductAbstracts(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation($productLabelTransfer->getIdProductLabelOrFail(), $productTransfer->getFkProductAbstractOrFail());

        // Assert
        $this->assertProductEventWithProductAbstractIdIsEmitted($productTransfer->getFkProductAbstractOrFail());

        // Act
        $this->tester->getFacade()->triggerProductAbstractUpdateEventsByProductLabelEvents([
                (new EventEntityTransfer())->setId($productLabelTransfer->getIdProductLabelOrFail()),
           ]);
    }

    /**
     * @return void
     */
    public function testTriggerProductAbstractUpdateEventsByProductLabelLocalizedAttributeEventsTriggerEventsWithCorrectProductAbstracts(): void
    {
        // Arrange
        $productTransfer = $this->tester->haveProduct();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation($productLabelTransfer->getIdProductLabelOrFail(), $productTransfer->getFkProductAbstractOrFail());

        // Assert
        $this->assertProductEventWithProductAbstractIdIsEmitted($productTransfer->getFkProductAbstractOrFail());

        // Act
        $this->tester->getFacade()->triggerProductAbstractUpdateEventsByProductLabelEvents([
                (new EventEntityTransfer())
                    ->setName('spy_product_label_localized_attributes')
                    ->setForeignKeys(['spy_product_label_localized_attributes.fk_product_label' => $productLabelTransfer->getIdProductLabelOrFail()]),
            ]);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function assertProductEventWithProductAbstractIdIsEmitted(int $idProductAbstract): void
    {
        $this->eventFacade
            ->expects($this->atLeastOnce())
            ->method('triggerBulk')
            ->with(static::PRODUCT_ABSTRACT_UPDATE, $this->callback(
                function ($transfers) use ($idProductAbstract) {
                    $this->assertNotEmpty($transfers);
                    $this->assertInstanceOf(EventEntityTransfer::class, $transfers[0]);
                    $this->assertEquals($transfers[0]->getId(), $idProductAbstract);

                    return true;
                },
            ));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface
     */
    protected function createTouchFacadeMock(): ProductLabelToTouchInterface
    {
        return $this->createMock(ProductLabelToTouchInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToProductInterface
     */
    protected function createProductFacadeMock(): ProductLabelToProductInterface
    {
        return $this->createMock(ProductLabelToProductInterface::class);
    }
}
