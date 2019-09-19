<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipProductList;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\SpyMerchantRelationshipEntityTransfer;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantRelationshipProductListBusinessTester extends Actor
{
    use _generated\MerchantRelationshipProductListBusinessTesterActions;

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): MerchantRelationshipFacadeInterface
    {
        return $this->getLocator()->merchantRelationship()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    public function getProductListFacade(): ProductListFacadeInterface
    {
        return $this->getLocator()->productList()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationship(): MerchantRelationshipTransfer
    {
        $idMerchant = $this->haveMerchant()->getIdMerchant();
        $idCompanyBusinessUnit = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->haveCompany()->getIdCompany(),
        ])->getIdCompanyBusinessUnit();

        $merchantRelationship = $this->haveMerchantRelationship([
            SpyMerchantRelationshipEntityTransfer::FK_MERCHANT => $idMerchant,
            SpyMerchantRelationshipEntityTransfer::FK_COMPANY_BUSINESS_UNIT => $idCompanyBusinessUnit,
            SpyMerchantRelationshipEntityTransfer::MERCHANT_RELATIONSHIP_KEY => uniqid(),
        ]);

        return $this->getMerchantRelationshipFacade()->createMerchantRelationship($merchantRelationship);
    }

    /**
     * @param int $idProductList
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer|null
     */
    public function findProductListById(int $idProductList): ?ProductListTransfer
    {
        return $this->getLocator()
            ->productList()
            ->facade()
            ->getProductListById(
                (new ProductListTransfer())
                    ->setIdProductList($idProductList)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationship
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function createProductListWithMerchantRelationship(MerchantRelationshipTransfer $merchantRelationship): ProductListTransfer
    {
        $productList = $this->haveProductList();
        $productList->setFkMerchantRelationship($merchantRelationship->getIdMerchantRelationship());

        return $this->getProductListFacade()->saveProductList($productList);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function createProductList(): ProductListTransfer
    {
        return $this->haveProductList();
    }

    /**
     * @return void
     */
    public function clearProductListTable(): void
    {
        $this->getProductListQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function truncateProductListTableRelations(): void
    {
        $this->truncateTableRelations($this->getProductListQuery());
    }

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    protected function getProductListQuery(): SpyProductListQuery
    {
        return SpyProductListQuery::create();
    }
}
