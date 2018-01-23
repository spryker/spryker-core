<?php
/**
 * Created by PhpStorm.
 * User: ahmedsabaa
 * Date: 1/23/18
 * Time: 3:53 PM
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;


use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

interface ProductCategoryFilterTransferGeneratorInterface
{
    /**
     * @param int $idProductCategoryFilter
     * @param int $idCategory
     * @param string $jsonData
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferFromJson($idProductCategoryFilter, $idCategory, $jsonData);

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferWithJsonFromTransfer(ProductCategoryFilterTransfer $productCategoryFilterTransfer);
}