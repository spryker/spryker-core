<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Communication\Controller;

use Generated\Shared\Transfer\ProductCategoryTransfer;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;

/**
 * @method ProductCategoryFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param ProductCategoryTransfer $productCategoryTransfer
     *
     * @return ProductCategoryTransfer
     */
    public function getProductsByIdCategoryAction(ProductCategoryTransfer $productCategoryTransfer)
    {
        return $this->getFacade()->getProductsByIdCategory($productCategoryTransfer);
    }
}
