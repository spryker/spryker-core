<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
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
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function addProduct(ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFactory()
            ->createProductApi()
            ->add($apiDataTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
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
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function updateProduct($idProductAbstract, ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFactory()
            ->createProductApi()
            ->update($idProductAbstract, $apiDataTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function deleteProduct($idProduct)
    {
        return $this->getFactory()
            ->createProductApi()
            ->delete($idProduct);
    }

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
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return array
     */
    public function validate(ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFactory()
            ->createProductApiValidator()
            ->validate($apiDataTransfer);
    }

}
