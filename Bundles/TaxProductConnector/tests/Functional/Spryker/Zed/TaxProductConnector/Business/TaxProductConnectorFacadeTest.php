<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\TaxProductConnector\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Tax\Business\TaxFacade;
use Spryker\Zed\TaxProductConnector\Business\Exception\ProductAbstractNotFoundException;
use Spryker\Zed\TaxProductConnector\Business\Exception\TaxSetNotFoundException;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group TaxProductConnector
 * @group Business
 * @group TaxProductConnectorFacadeTest
 */
class TaxProductConnectorFacadeTest extends Test
{

    /**
     * @return void
     */
    public function testSaveTaxSetToProductAbstractShouldPersistTaxSetId()
    {
        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();

        $taxSetTransfer = $this->createTaxSet();
        $productAbstractTransfer = $this->createProductAbstract();

        $productAbstractTransfer->setIdTaxSet($taxSetTransfer->getIdTaxSet());

        $productAbstractTransfer = $taxProductConnectorFacade->saveTaxSetToProductAbstract($productAbstractTransfer);
        $productAbstractTransfer = $this->createProductFacade()
            ->getProductAbstractById($productAbstractTransfer->getIdProductAbstract());

        $this->assertEquals($taxSetTransfer->getIdTaxSet(), $productAbstractTransfer->getIdTaxSet());

    }

    /**
     * @return void
     */
    public function testSaveTaxSetToProductWhenProductDoesNotExistShouldThrowException()
    {
        $this->expectException(ProductAbstractNotFoundException::class);

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setIdProductAbstract(-1);

        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();
        $taxProductConnectorFacade->saveTaxSetToProductAbstract($productAbstractTransfer);

    }

    /**
     * @return void
     */
    public function testSaveTaxSetToProductWhenProductIdNotGivenShouldThrowException()
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();
        $taxProductConnectorFacade->saveTaxSetToProductAbstract(new ProductAbstractTransfer());

    }

    /**
     * @return void
     */
    public function testAddTaxSetShouldAssignTaxSetIdToTransfer()
    {
        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();

        $taxSetTransfer = $this->createTaxSet();
        $productAbstractTransfer = $this->createProductAbstract();

        $productAbstractTransfer->setIdTaxSet($taxSetTransfer->getIdTaxSet());

        $taxProductConnectorFacade->saveTaxSetToProductAbstract($productAbstractTransfer);

        $productAbstractTransfer->setIdTaxSet(null);

        $productAbstractTransfer = $taxProductConnectorFacade->addTaxSet($productAbstractTransfer);

        $this->assertEquals($productAbstractTransfer->getIdTaxSet(), $taxSetTransfer->getIdTaxSet());

    }

    /**
     * @return void
     */
    public function testAddTaxSetWhenTaxSetDoesNotExistShouldThrowException()
    {
        $this->expectException(TaxSetNotFoundException::class);

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setIdProductAbstract(-1);

        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();
        $taxProductConnectorFacade->addTaxSet($productAbstractTransfer);

    }

    /**
     * @return void
     */
    public function testAddTaxSetWhenProductIdNotGivenShouldThrowException()
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();
        $taxProductConnectorFacade->addTaxSet(new ProductAbstractTransfer());

    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacade
     */
    protected function createTaxProductConnectorFacade()
    {
        return new TaxProductConnectorFacade();
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacade
     */
    protected function createProductFacade()
    {
        return new ProductFacade();
    }

    /**
     * @return \Spryker\Zed\Tax\Business\TaxFacade
     */
    protected function createTaxFacade()
    {
        return new TaxFacade();
    }

    /**
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    protected function createTaxSet()
    {
        $taxFacade = $this->createTaxFacade();

        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setAmount(50);
        $taxSetTransfer->setEffectiveRate(15);
        $taxSetTransfer->setName('test tax set');

        $taxSetTransfer = $taxFacade->createTaxSet($taxSetTransfer);

        return $taxSetTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstract()
    {
        $productFacade = $this->createProductFacade();
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku('test-sku-123');

        $idProductAbstractTransfer = $productFacade->createProductAbstract($productAbstractTransfer);
        $productAbstractTransfer->setIdProductAbstract($idProductAbstractTransfer);

        return $productAbstractTransfer;
    }

}
