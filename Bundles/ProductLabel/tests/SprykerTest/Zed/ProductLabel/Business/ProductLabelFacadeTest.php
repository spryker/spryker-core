<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabel\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ProductLabelBuilder;
use Generated\Shared\DataBuilder\ProductLabelLocalizedAttributesBuilder;

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
    public function testReadLabelReturnsProductLabelTransfer()
    {
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelFacade->createLabel($productLabelTransfer);

        $productLabelTransfer = $productLabelFacade->readLabel($productLabelTransfer->getIdProductLabel());

        $this->assertInstanceOf('\Generated\Shared\Transfer\ProductLabelTransfer', $productLabelTransfer);
    }

    /**
     * @expectedException \Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException
     *
     * @return void
     */
    public function testRealLabelThrowsExceptionIfLabelDoesNotExist()
    {
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->readLabel(1);
    }

    /**
     * @return void
     */
    public function testRealLabelReturnsCollectionOfLocalizedAttributes()
    {
        $localeTransfer = $this->tester->haveLocale();

        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelTransfer->addLocalizedAttributes(
            $this->generateLocalizedAttributesTransfer($localeTransfer->getIdLocale())
        );
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->createLabel($productLabelTransfer);

        $persistedProductLabelTransfer = $productLabelFacade->readLabel($productLabelTransfer->getIdProductLabel());

        $this->assertSame(1, $persistedProductLabelTransfer->getLocalizedAttributesCollection()->count());
    }

    /**
     * @return void
     */
    public function testReadAllLabelsReturnsCollectionSortedByPosition()
    {
        $this->tester->haveProductLabel();
        $this->tester->haveProductLabel();
        $this->tester->haveProductLabel();
        $this->tester->haveProductLabel();

        $productLabelFacade = $this->createProductLabelFacade();
        /** @var \ArrayObject $productLabelTransferCollection */
        $productLabelTransferCollection = $productLabelFacade->readAllLabels();

        $this->assertSame(1, $productLabelTransferCollection[0]->getPosition());
        $this->assertSame(2, $productLabelTransferCollection[1]->getPosition());
        $this->assertSame(3, $productLabelTransferCollection[2]->getPosition());
        $this->assertSame(4, $productLabelTransferCollection[3]->getPosition());
    }

    /**
     * @return void
     */
    public function testCreateLabelPersistsDataAndUpdatesTransferIdField()
    {
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelFacade->createLabel($productLabelTransfer);

        $persistedProductLabelTransfer = $productLabelFacade->readLabel($productLabelTransfer->getIdProductLabel());

        $this->assertNotNull($productLabelTransfer->getIdProductLabel());
        $this->assertSame($productLabelTransfer->getIdProductLabel(), $persistedProductLabelTransfer->getIdProductLabel());
    }

    /**
     * @return void
     */
    public function testUpdateLabelPersistsChanges()
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

        $updatedProductLabelTransfer = $productLabelFacade->readLabel($productLabelTransfer->getIdProductLabel());

        $this->assertFalse($updatedProductLabelTransfer->getIsActive());
        $this->assertTrue($updatedProductLabelTransfer->getIsExclusive());
        $this->assertSame('FooBarBaz', $updatedProductLabelTransfer->getName());
    }

    /**
     * @return void
     */
    public function testCreateLabelPersistsLocalizedAttributes()
    {
        $localeTransfer = $this->tester->haveLocale();

        $productLabelTransfer = (new ProductLabelBuilder())->except(['idProductLabel'])->build();
        $productLabelTransfer->addLocalizedAttributes(
            $this->generateLocalizedAttributesTransfer($localeTransfer->getIdLocale())
        );
        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->createLabel($productLabelTransfer);

        $persistedProductLabelTransfer = $productLabelFacade->readLabel($productLabelTransfer->getIdProductLabel());

        $this->assertSame(1, $persistedProductLabelTransfer->getLocalizedAttributesCollection()->count());

        /** @var \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[] $localizedAttributesList */
        $localizedAttributesList = $persistedProductLabelTransfer->getLocalizedAttributesCollection()->getArrayCopy();
        $this->assertSame($productLabelTransfer->getIdProductLabel(), $localizedAttributesList[0]->getFkProductLabel());
        $this->assertSame($localeTransfer->getIdLocale(), $localizedAttributesList[0]->getFkLocale());
    }

    /**
     * @return void
     */
    public function testSetAbstractProductRelationPersistsRelation()
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->setAbstractProductRelationForLabel($idProductLabel, $idProductAbstract);
        $productLabelTransferCollection = $productLabelFacade->readLabelsForAbstractProduct($idProductAbstract);

        $this->assertCount(1, $productLabelTransferCollection);
    }

    /**
     * @return void
     */
    public function testDeleteAbstractProductRelationRemovesFromPersistence()
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $productLabelFacade = $this->createProductLabelFacade();
        $productLabelFacade->setAbstractProductRelationForLabel($idProductLabel, $idProductAbstract);

        $productLabelTransferCollection = $productLabelFacade->readLabelsForAbstractProduct($idProductAbstract);
        $this->assertCount(1, $productLabelTransferCollection);

        $productLabelFacade->removeAbstractProductRelationForLabel($idProductLabel, $idProductAbstract);
        $productLabelTransferCollection = $productLabelFacade->readLabelsForAbstractProduct($idProductAbstract);
        $this->assertCount(0, $productLabelTransferCollection);
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
