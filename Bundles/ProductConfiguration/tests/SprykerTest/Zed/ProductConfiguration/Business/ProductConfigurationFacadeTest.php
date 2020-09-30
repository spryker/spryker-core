<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfiguration
 * @group Business
 * @group Facade
 * @group ProductConfigurationFacadeTest
 * Add your own group annotations below this line
 */
class ProductConfigurationFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductConfiguration\ProductConfigurationBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductConfigurationCollectionRetrievesCollection(): void
    {
        //Arrange
        $productTransfer = $this->tester->haveProduct();

        $productConfigurationTransfer = $this->tester->haveProductConfiguration(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $productTransfer->getIdProductConcrete(),
            ]
        );

        $productConfigurationCriteriaTransfer = (new ProductConfigurationFilterTransfer())
            ->setProductConfigurationIds([$productConfigurationTransfer->getIdProductConfiguration()]);

        //Act
        $productConfigurationCollectionTransfer = $this->tester->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        /** @var \Generated\Shared\Transfer\ProductConfigurationTransfer $createdProductConfigurationTransfer */
        $createdProductConfigurationTransfer = $productConfigurationCollectionTransfer->getProductConfigurations()
            ->getIterator()->current();

        //Assert
        $this->assertNotEmpty($productConfigurationCollectionTransfer->getProductConfigurations());
        $this->assertSame($productTransfer->getIdProductConcrete(), $createdProductConfigurationTransfer->getFkProduct());
    }

    /**
     * @return void
     */
    public function testGetProductConfigurationCollectionWithWrongProductFkRetrievesEmptyCollection(): void
    {
        //Arrange
        $productConfigurationCriteriaTransfer = (new ProductConfigurationFilterTransfer())
            ->setProductConfigurationIds([ProductConfigurationTransfer::FK_PRODUCT => 222]);

        //Act
        $productConfigurationCollectionTransfer = $this->tester->getFacade()
            ->getProductConfigurationCollection($productConfigurationCriteriaTransfer);

        //Assert
        $this->assertEmpty($productConfigurationCollectionTransfer->getProductConfigurations());
    }
}
