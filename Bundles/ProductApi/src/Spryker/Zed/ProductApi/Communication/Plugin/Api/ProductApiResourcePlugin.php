<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Communication\Plugin\Api;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductApi\ProductApiConfig;

/**
 * @method \Spryker\Zed\ProductApi\Business\ProductApiFacade getFacade()
 * @method \Spryker\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 */
class ProductApiResourcePlugin extends AbstractPlugin implements ApiResourcePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFacade()->addProduct($apiDataTransfer);
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($idProduct, ApiFilterTransfer $apiFilterTransfer)
    {
        return $this->getFacade()->getProduct($idProduct, $apiFilterTransfer);
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function update($idProduct, ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFacade()->updateProduct($idProduct, $apiDataTransfer);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function delete($idProduct)
    {
        return $this->getFacade()->deleteProduct($idProduct);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFacade()->findProducts($apiRequestTransfer);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResourceName()
    {
        return ProductApiConfig::RESOURCE_PRODUCTS;
    }

}
