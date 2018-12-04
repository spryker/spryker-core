<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\ProductListCollectionTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface getRepository()
 */
class MerchantRelationshipProductListFacade extends AbstractFacade implements MerchantRelationshipProductListFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithProductListIds(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->getFactory()
            ->createCustomerExpander()
            ->expandCustomerTransferWithProductListIds($customerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCollectionTransfer
     */
    public function getProductListCollectionByMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): ProductListCollectionTransfer
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getProductListCollectionByMerchantRelationship($merchantRelationshipTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function clearMerchantRelationshipFromProductList(ProductListTransfer $productListTransfer): ProductListTransfer
    {
        return $this->getFactory()
            ->createProductListWriter()
            ->clearMerchantRelationshipFromProductList($productListTransfer);
    }
}
