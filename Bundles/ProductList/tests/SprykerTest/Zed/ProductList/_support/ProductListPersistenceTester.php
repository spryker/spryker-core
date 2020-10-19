<?php
namespace SprykerTest\Zed\ProductList;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class ProductListPersistenceTester extends Actor
{
    use _generated\ProductListPersistenceTesterActions;

    /**
     * @param int $idProductList
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\ProductListCategoryRelationTransfer
     */
    public function haveProductListCategory(int $idProductList, int $idCategory): ProductListCategoryRelationTransfer
    {
        $productListProductCategoryEntity = SpyProductListCategoryQuery::create()
            ->filterByFkProductList($idProductList)
            ->filterByFkCategory($idCategory)
            ->findOneOrCreate();

        if ($productListProductCategoryEntity->isNew()) {
            $productListProductCategoryEntity->save();
        }

        return (new ProductListCategoryRelationTransfer())
            ->setCategoryIds([$productListProductCategoryEntity->getFkCategory()])
            ->setIdProductList($productListProductCategoryEntity->getFkProductList());
    }
}
