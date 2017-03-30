<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

interface ProductApiFacadeInterface
{

    /**
     * Specification:
     *  - Finds Products by filter transcer, including sort, conditions and pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function findProducts(ApiRequestTransfer $apiRequestTransfer);

    /**
     * Specification:
     *  - Finds Product by Product ID.
     *  - Throws ProductNotFoundException if not found.
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function getProduct($idProduct, ApiFilterTransfer $apiFilterTransfer);

    /**
     * Specification:
     *  - Adds new Product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function addProduct(ApiDataTransfer $apiDataTransfer);

    /**
     * Specification:
     *  - Finds Product by Product ID.
     *  - Throws ProductNotFoundException if not found.
     *  - Entity is modified with data from ProductTransfer and saved.
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return void
     */
    public function updateProduct($idProduct, ApiDataTransfer $apiDataTransfer);

    /**
     * Specification:
     *  - Finds Product by Product ID.
     *  - Throws ProductNotFoundException if not found.
     *  - Deletes Product.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return void
     */
    public function deleteProduct($idProduct);

}
