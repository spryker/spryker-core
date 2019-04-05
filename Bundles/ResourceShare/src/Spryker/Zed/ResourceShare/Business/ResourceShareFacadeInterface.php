<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;

interface ResourceShareFacadeInterface
{
    /**
     * Specification:
     * - Generate share resource for the provided customer.
     * - Sets UUID in returned transfer if generation was successful.
     * - Sets `isSuccessful=true` if generation was successful, adds error messages otherwise.
     * - Does not create new UUID if the provided "resourceType" and "resourceData" pair exists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer;

    /**
     * Specification:
     * - Returns with "isSuccess=false" and error message if resource is not found by provided uuid.
     * - Applies corresponding strategy using `ResourceShareActivatorStrategyPluginInterface` stack.
     * - Returns with "isLoginRequired=true" when strategy expects logged in customer.
     * - Returns with "isSuccess=true" on success or with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer;

    /**
     * Specification:
     * - Retrieves the share resource by the provided UUID.
     * - Returns "isSuccess=true" on success and error message otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShareByUuid(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer;
}
