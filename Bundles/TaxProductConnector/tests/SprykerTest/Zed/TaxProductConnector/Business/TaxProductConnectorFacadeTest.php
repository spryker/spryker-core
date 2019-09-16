<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetResponseTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Tax\Business\TaxFacade;
use Spryker\Zed\Tax\Business\TaxFacadeInterface;
use Spryker\Zed\TaxProductConnector\Business\Exception\ProductAbstractNotFoundException;
use Spryker\Zed\TaxProductConnector\Business\Exception\TaxSetNotFoundException;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacade;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacadeInterface;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractReadPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group TaxProductConnector
 * @group Business
 * @group Facade
 * @group TaxProductConnectorFacadeTest
 * Add your own group annotations below this line
 */
class TaxProductConnectorFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testSaveTaxSetToProductAbstractShouldPersistTaxSetId(): void
    {
        $this->tester->setDependency(
            ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_READ,
            $this->getProductAbstractReadPlugins()
        );
        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();

        $taxSetTransfer = $this->createTaxSet();
        $productAbstractTransfer = $this->createProductAbstract();

        $productAbstractTransfer->setIdTaxSet($taxSetTransfer->getIdTaxSet());

        $productAbstractTransfer = $taxProductConnectorFacade->saveTaxSetToProductAbstract($productAbstractTransfer);
        $productAbstractTransfer = $this->createProductFacade()
            ->findProductAbstractById($productAbstractTransfer->getIdProductAbstract());

        $this->assertEquals($taxSetTransfer->getIdTaxSet(), $productAbstractTransfer->getIdTaxSet());
    }

    /**
     * @return void
     */
    public function testSaveTaxSetToProductWhenProductDoesNotExistShouldThrowException(): void
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
    public function testSaveTaxSetToProductWhenProductIdNotGivenShouldThrowException(): void
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();
        $taxProductConnectorFacade->saveTaxSetToProductAbstract(new ProductAbstractTransfer());
    }

    /**
     * @return void
     */
    public function testAddTaxSetShouldAssignTaxSetIdToTransfer(): void
    {
        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();

        $taxSetTransfer = $this->createTaxSet();
        $productAbstractTransfer = $this->createProductAbstract();

        $productAbstractTransfer->setIdTaxSet($taxSetTransfer->getIdTaxSet());

        $taxProductConnectorFacade->saveTaxSetToProductAbstract($productAbstractTransfer);

        $productAbstractTransfer->setIdTaxSet(null);

        $productAbstractTransfer = $taxProductConnectorFacade->mapTaxSet($productAbstractTransfer);

        $this->assertEquals($productAbstractTransfer->getIdTaxSet(), $taxSetTransfer->getIdTaxSet());
    }

    /**
     * @return void
     */
    public function testAddTaxSetWhenTaxSetDoesNotExistShouldThrowException(): void
    {
        $this->expectException(TaxSetNotFoundException::class);

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setIdProductAbstract(-1);

        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();
        $taxProductConnectorFacade->mapTaxSet($productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testAddTaxSetWhenProductIdNotGivenShouldThrowException(): void
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();
        $taxProductConnectorFacade->mapTaxSet(new ProductAbstractTransfer());
    }

    /**
     * @return void
     */
    public function testGettingTaxRatesByProductAbstract(): void
    {
        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();

        $taxSetTransfer = $this->createTaxSet();
        $productAbstractTransfer = $this->createProductAbstract();

        $productAbstractTransfer->setIdTaxSet($taxSetTransfer->getIdTaxSet());

        $productAbstractTransfer = $taxProductConnectorFacade->saveTaxSetToProductAbstract($productAbstractTransfer);

        $taxSetsResponseTransfer = $taxProductConnectorFacade->getTaxSetForProductAbstract($productAbstractTransfer);

        $this->assertInstanceOf(TaxSetResponseTransfer::class, $taxSetsResponseTransfer);
        $this->assertTrue($taxSetsResponseTransfer->getIsSuccess());
        $this->assertEmpty($taxSetsResponseTransfer->getError());
        $this->assertNotEmpty($taxSetsResponseTransfer->getTaxSet()->getUuid());
        $this->assertCount(1, $taxSetsResponseTransfer->getTaxSet()->getTaxRates());
    }

    /**
     * @return void
     */
    public function testGettingTaxRatesByNonExistentProductAbstract(): void
    {
        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();
        $productAbstractTransfer = (new ProductAbstractTransfer())->setSku('non-existent-sku-52892');

        $taxSetsResponseTransfer = $taxProductConnectorFacade->getTaxSetForProductAbstract($productAbstractTransfer);

        $this->assertInstanceOf(TaxSetResponseTransfer::class, $taxSetsResponseTransfer);
        $this->assertFalse($taxSetsResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($taxSetsResponseTransfer->getError());
        $this->assertNull($taxSetsResponseTransfer->getTaxSet());
    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacadeInterface
     */
    protected function createTaxProductConnectorFacade(): TaxProductConnectorFacadeInterface
    {
        return new TaxProductConnectorFacade();
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacade
     */
    protected function createProductFacade(): ProductFacadeInterface
    {
        return new ProductFacade();
    }

    /**
     * @return \Spryker\Zed\Tax\Business\TaxFacade
     */
    protected function createTaxFacade(): TaxFacadeInterface
    {
        return new TaxFacade();
    }

    /**
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    protected function createTaxSet(): TaxSetTransfer
    {
        $taxFacade = $this->createTaxFacade();

        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setAmount(50);
        $taxSetTransfer->setEffectiveRate(15);
        $taxSetTransfer->setName('test tax set');
        $taxSetTransfer->addTaxRate(
            (new TaxRateTransfer())
                ->setName('test tax rate')
                ->setFkCountry(60)
                ->setRate(19.00)
        );

        $taxSetTransfer = $taxFacade->createTaxSet($taxSetTransfer);

        return $taxSetTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstract(): ProductAbstractTransfer
    {
        $productFacade = $this->createProductFacade();
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku('test-sku-123');
        $productAbstractTransfer->setStoreRelation((new StoreRelationTransfer())->setIdStores([]));

        $idProductAbstractTransfer = $productFacade->createProductAbstract($productAbstractTransfer);
        $productAbstractTransfer->setIdProductAbstract($idProductAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface[]
     */
    protected function getProductAbstractReadPlugins()
    {
        return [
            new TaxSetProductAbstractReadPlugin(),
        ];
    }
}
