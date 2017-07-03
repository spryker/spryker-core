<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabel\Business;

use Codeception\TestCase\Test;
use DateTime;
use Generated\Shared\DataBuilder\ProductLabelBuilder;
use Generated\Shared\DataBuilder\ProductLabelLocalizedAttributesBuilder;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Shared\ProductLabel\ProductLabelConstants;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductLabel
 * @group Business
 * @group Facade
 * @group ProductLabelFacadeTest
 * Add your own group annotations below this line
 */
class ProductLabelFacadeTest extends Test
{

    /**
     * @var \SprykerTest\Zed\ProductLabel\BusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindLabelByIdShouldReturnProductLabelTransfer()
    {
        $productLabelTransfer = $this->tester->haveProductLabel();

        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertInstanceOf('\Generated\Shared\Transfer\ProductLabelTransfer', $productLabelTransfer);
    }

    /**
     * @return void
     */
    public function testFindLabelByIdShouldReturnNullIfLabelDoesNotExist()
    {
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelTransfer = $productLabelFacade->findLabelById(666);

        $this->assertNull($productLabelTransfer);
    }

    /**
     * @return void
     */
    public function testFindLabelByIdShouldReturnCollectionOfLocalizedAttributes()
    {
        $localeTransfer = $this->tester->haveLocale();

        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelTransfer->addLocalizedAttributes(
            $this->generateLocalizedAttributesTransfer($localeTransfer->getIdLocale())
        );
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->createLabel($productLabelTransfer);

        $persistedProductLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertSame(1, $persistedProductLabelTransfer->getLocalizedAttributesCollection()->count());
    }

    /**
     * @return void
     */
    public function testFindAllLabelsShouldReturnCollectionSortedByPosition()
    {
        $this->tester->haveProductLabel();
        $this->tester->haveProductLabel();
        $this->tester->haveProductLabel();
        $this->tester->haveProductLabel();

        $productLabelFacade = $this->createProductLabelFacade();
        /** @var \ArrayObject $productLabelTransferCollection */
        $productLabelTransferCollection = $productLabelFacade->findAllLabels();

        $this->assertSame(1, $productLabelTransferCollection[0]->getPosition());
        $this->assertSame(2, $productLabelTransferCollection[1]->getPosition());
        $this->assertSame(3, $productLabelTransferCollection[2]->getPosition());
        $this->assertSame(4, $productLabelTransferCollection[3]->getPosition());
    }

    /**
     * @return void
     */
    public function testCreateLabelShouldPersistDataAndUpdatesTransferIdField()
    {
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelFacade->createLabel($productLabelTransfer);

        $persistedProductLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertNotNull($productLabelTransfer->getIdProductLabel());
        $this->assertSame($productLabelTransfer->getIdProductLabel(), $persistedProductLabelTransfer->getIdProductLabel());
    }

    /**
     * @return void
     */
    public function testUpdateLabelShouldPersistChanges()
    {
        $productLabelTransfer = $this->tester->haveProductLabel([
            'idActive' => true,
            'isExclusive' => false,
        ]);

        $productLabelTransfer->setIsActive(false);
        $productLabelTransfer->setIsExclusive(true);
        $productLabelTransfer->setName('FooBarBaz');

        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->updateLabel($productLabelTransfer);

        $updatedProductLabelTransfer = $productLabelFacade->findLabelById($productLabelTransfer->getIdProductLabel());

        $this->assertFalse($updatedProductLabelTransfer->getIsActive());
        $this->assertTrue($updatedProductLabelTransfer->getIsExclusive());
        $this->assertSame('FooBarBaz', $updatedProductLabelTransfer->getName());
    }

    /**
     * @return void
     */
    public function testCreateLabelShouldPersistLocalizedAttributes()
    {
        $localeTransfer = $this->tester->haveLocale();

        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelTransfer->addLocalizedAttributes(
            $this->generateLocalizedAttributesTransfer($localeTransfer->getIdLocale())
        );
        $productLabelFacade = $this->createProductLabelFacade();
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
    public function testCreateLabelShouldTouchDictionaryActive()
    {
        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->createLabel($productLabelTransfer);

        $this->tester->assertTouchActive(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER
        );
    }

    /**
     * @return void
     */
    public function testFindProductAbstractRelationsByIdProductLabelShouldReturnListOfIdsProductAbstract()
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        $productLabelFacade = $this->createProductLabelFacade();
        $idsProductAbstract = $productLabelFacade->findProductAbstractRelationsByIdProductLabel($idProductLabel);

        $this->assertCount(1, $idsProductAbstract);
        $this->assertSame($idProductAbstract, $idsProductAbstract[0]);
    }

    /**
     * @return void
     */
    public function testAddProductAbstractRelationsShouldPersistRelations()
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->addAbstractProductRelationsForLabel($idProductLabel, [$idProductAbstract]);

        $productLabelTransferCollection = $productLabelFacade->findLabelsByIdProductAbstract($idProductAbstract);

        $this->assertCount(1, $productLabelTransferCollection);
    }

    /**
     * @return void
     */
    public function testFindProductLabelIdsByIdProductAbstractShouldReturnListOfIds()
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelIds = $productLabelFacade->findLabelIdsByIdProductAbstract($idProductAbstract);

        $this->assertSame([$idProductLabel], $productLabelIds);
    }

    /**
     * @return void
     */
    public function testRemoveProductAbstractRelationsShouldRemoveExistingRelations()
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->removeProductAbstractRelationsForLabel($idProductLabel, [$idProductAbstract]);

        $idsProductAbstract = $productLabelFacade->findProductAbstractRelationsByIdProductLabel($idProductLabel);

        $this->assertCount(0, $idsProductAbstract);
    }

    /**
     * @return void
     */
    public function testAddProductAbstractRelationsShouldTouchRelationsActive()
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $productLabelFacade = $this->createProductLabelFacade();
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
    public function testCheckLabelValidityDateRangeAndTouchTouchesUnpublishedLabelsActiveWhenEnteringValidityDateRange()
    {
        $this->tester->haveProductLabel([
            'validFrom' => (new DateTime())->setTimestamp(strtotime('-1 day'))->format('Y-m-d'),
            'validTo' => (new DateTime())->setTimestamp(strtotime('+1 day'))->format('Y-m-d'),
            'isPublished' => false,
        ]);

        sleep(1);

        $referenceTime = new DateTime('now');
        $productLabelFacade = $this->createProductLabelFacade();
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
    public function testCheckLabelValidityDateRangeAndTouchTouchesPublishedLabelsDeletedWhenLeavingValidityDateRange()
    {
        $this->tester->haveProductLabel([
            'validFrom' => (new DateTime())->setTimestamp(strtotime('-3 day'))->format('Y-m-d'),
            'validTo' => (new DateTime())->setTimestamp(strtotime('-1 day'))->format('Y-m-d'),
            'isPublished' => true,
        ]);

        sleep(1);

        $referenceTime = new DateTime('now');
        $productLabelFacade = $this->createProductLabelFacade();
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
    public function testCheckLabelValidityDateRangeAndTouchDoesNotTouchActiveWhenAlreadyPublished()
    {
        $this->tester->haveProductLabel([
            'validFrom' => (new DateTime())->setTimestamp(strtotime('-2 day'))->format('Y-m-d'),
            'validTo' => (new DateTime())->setTimestamp(strtotime('+1 day'))->format('Y-m-d'),
            'isPublished' => true,
        ]);
        $referenceTime = new DateTime('now');

        sleep(1);

        $productLabelFacade = $this->createProductLabelFacade();
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
    public function testCheckLabelValidityDateRangeAndTouchDoesNotTouchDeletedWhenAlreadyUnpublished()
    {
        $this->tester->haveProductLabel([
            'validFrom' => (new DateTime())->setTimestamp(strtotime('-3 day'))->format('Y-m-d'),
            'validTo' => (new DateTime())->setTimestamp(strtotime('-1 day'))->format('Y-m-d'),
            'isPublished' => false,
        ]);
        $referenceTime = new DateTime('now');

        sleep(1);

        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->checkLabelValidityDateRangeAndTouch();

        $this->tester->assertNoTouchAfter(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY_IDENTIFIER,
            $referenceTime
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected function createProductLabelFacade()
    {
        return $this->tester->getLocator()->productLabel()->facade();
    }

    /**
     * @param int|null $fkLocale
     * @param int|null $fkProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer
     */
    protected function generateLocalizedAttributesTransfer($fkLocale = null, $fkProductLabel = null)
    {
        $builder = new ProductLabelLocalizedAttributesBuilder([
            'fkProductLabel' => $fkProductLabel,
            'fkLocale' => $fkLocale,
        ]);

        return $builder->build();
    }

}
