<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

interface CustomerApiFacadeInterface
{
    /**
     * Specification:
     *  - Adds new customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function addCustomer(ApiDataTransfer $apiDataTransfer);

    /**
     * Specification:
     *  - Finds customer by customer ID.
     *  - Throws CustomerNotFoundException if not found.
     *
     * @api
     *
     * @internal param ApiFilterTransfer $apiFilterTransfer
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer $customerTransfer
     */
    public function getCustomer($idCustomer);

    /**
     * Specification:
     *  - Finds customer by customer ID.
     *  - Throws CustomerNotFoundException if not found.
     *  - Entity is modified with data from CustomerTransfer and saved.
     *
     * @api
     *
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function updateCustomer($idCustomer, ApiDataTransfer $apiDataTransfer);

    /**
     * Specification:
     *  - Finds customer by customer ID.
     *  - Throws CustomerNotFoundException if not found.
     *  - Deletes customer.
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function removeCustomer($idCustomer);

    /**
     * Specification:
     *  - Finds customers by filter transcer, including sort, conditions and pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function findCustomers(ApiRequestTransfer $apiRequestTransfer);

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
