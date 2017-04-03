<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

/**
 * @method \Spryker\Zed\ProductApi\Business\ProductApiBusinessFactory getFactory()
 */
interface ProductApiFacadeInterface
{

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function addProduct(ApiDataTransfer $apiDataTransfer);

    /**
     * Specification:
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function getProduct($idProduct, ApiFilterTransfer $apiFilterTransfer);

    /**
     * Specification:
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function updateProduct($idProduct, ApiDataTransfer $apiDataTransfer);

    /**
     * Specification:
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function removeProduct($idProduct);

    /**
     * Specification:
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function findProducts(ApiRequestTransfer $apiRequestTransfer);

    /**
     * Specification:
     * - Validates the given API data and returns an array of errors if necessary.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return array
     */
    public function validate(ApiDataTransfer $apiDataTransfer);

}
