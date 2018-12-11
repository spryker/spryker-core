<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipProductList\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\SpyMerchantRelationshipEntityTransfer;
use Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListFacadeInterface;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipProductList
 * @group Business
 * @group Facade
 * @group MerchantRelationshipProductListFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipProductListFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindProductListCollectionByMerchantRelationship(): void
    {
        $merchantRelationship = $this->createMerchantRelationship();
        $productList = $this->createProductListWithMerchantRelationship($merchantRelationship);
        $productList2 = $this->createProductListWithMerchantRelationship($merchantRelationship);

        //Get collection of product lists by merchant relationship
        $productListCollection = $this->getFacade()
            ->findProductListCollectionByMerchantRelationship($merchantRelationship);

        $this->assertCount(2, $productListCollection->getProductLists());

        foreach ($productListCollection->getProductLists() as $productListTransfer) {
            $this->assertEquals($merchantRelationship->getIdMerchantRelationship(), $productListTransfer->getFkMerchantRelationship());
        }

        //Remove product lists and merchant relationship
        $this->getProductListFacade()->deleteProductList($productList);
        $this->getProductListFacade()->deleteProductList($productList2);
        $this->getMerchantRelationshipFacade()->deleteMerchantRelationship($merchantRelationship);
    }

    /**
     * @return void
     */
    public function testClearMerchantRelationshipFromProductList(): void
    {
        $merchantRelationship = $this->createMerchantRelationship();
        $productList = $this->createProductListWithMerchantRelationship($merchantRelationship);

        $productList = $this->getFacade()->clearMerchantRelationshipFromProductList($productList);

        $this->assertNotEquals($merchantRelationship->getIdMerchantRelationship(), $productList->getFkMerchantRelationship());
        $this->assertNull($productList->getFkMerchantRelationship());

        $this->getProductListFacade()->deleteProductList($productList);
        $this->getMerchantRelationshipFacade()->deleteMerchantRelationship($merchantRelationship);
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function createMerchantRelationship(): MerchantRelationshipTransfer
    {
        $idMerchant = $this->tester->haveMerchant()->getIdMerchant();
        $idCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit()->getIdCompanyBusinessUnit();

        $merchantRelationship = $this->tester->haveMerchantRelationship([
            SpyMerchantRelationshipEntityTransfer::MERCHANT_RELATIONSHIP_KEY => 'test',
            SpyMerchantRelationshipEntityTransfer::FK_MERCHANT => $idMerchant,
            SpyMerchantRelationshipEntityTransfer::FK_COMPANY_BUSINESS_UNIT => $idCompanyBusinessUnit,
        ]);

        return $this->getMerchantRelationshipFacade()->createMerchantRelationship($merchantRelationship);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationship
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function createProductListWithMerchantRelationship(MerchantRelationshipTransfer $merchantRelationship): ProductListTransfer
    {
        $productList = $this->tester->haveProductList();
        $productList->setFkMerchantRelationship($merchantRelationship->getIdMerchantRelationship());

        return $this->getProductListFacade()->saveProductList($productList);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListFacadeInterface
     */
    protected function getFacade(): MerchantRelationshipProductListFacadeInterface
    {
        return $this->tester->getLocator()->merchantRelationshipProductList()->facade();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface
     */
    protected function getMerchantRelationshipFacade(): MerchantRelationshipFacadeInterface
    {
        return $this->tester->getLocator()->merchantRelationship()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    protected function getProductListFacade(): ProductListFacadeInterface
    {
        return $this->tester->getLocator()->productList()->facade();
    }
}
