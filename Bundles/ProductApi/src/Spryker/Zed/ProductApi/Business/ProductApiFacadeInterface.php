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

interface ProductApiFacadeInterface
{

    /**
     * Specification:
     *  - Finds customers by filter transcer, including sort, conditions and pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Spryker\Zed\Api\Business\Model\ApiCollectionInterface
     */
    public function findProducts(ApiRequestTransfer $apiRequestTransfer);

    /**
     * Specification:
     *  - Finds customer by customer ID.
     *  - Throws CustomerNotFoundException if not found.
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
     *  - Adds new customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductApiTransfer $productApiTransfer
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function addCustomer(ProductApiTransfer $productApiTransfer);

    /**
     * Specification:
     *  - Finds customer by customer ID.
     *  - Throws CustomerNotFoundException if not found.
     *  - Entity is modified with data from CustomerTransfer and saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @throws \Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException
     *
     * @return void
     */
    public function updateCustomer(CustomerTransfer $customerTransfer);

    /**
     * Specification:
     *  - Finds customer by customer ID.
     *  - Throws CustomerNotFoundException if not found.
     *  - Deletes customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @throws \Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException
     *
     * @return void
     */
    public function deleteCustomer(CustomerTransfer $customerTransfer);

}
