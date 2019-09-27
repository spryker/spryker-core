<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface getRepository()
 */
class MerchantRelationshipProductListFacade extends AbstractFacade implements MerchantRelationshipProductListFacadeInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function deleteProductListsByMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $this->getFactory()
            ->createProductListWriter()
            ->deleteProductListsByMerchantRelationship($merchantRelationshipTransfer);
    }
}
