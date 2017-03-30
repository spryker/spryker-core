<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business;

use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductApi\Business\ProductApiBusinessFactory getFactory()
 */
class ProductApiFacade extends AbstractFacade implements ProductApiFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function findProducts(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFactory()
            ->createProductApi()
            ->find($apiRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function getProduct($idProduct, ApiFilterTransfer $apiFilterTransfer)
    {
        return $this->getFactory()
            ->createProductApi()
            ->get($idProduct, $apiFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductApiTransfer $productApiTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function addCustomer(ProductApiTransfer $productApiTransfer)
    {
        return $this->getFactory()
            ->createProductApi()
            ->add($productApiTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createProductApi()
            ->update($customerTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->getFactory()
            ->createProductApi()
            ->delete($customerTransfer);
    }

}
