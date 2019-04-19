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
     * - Generates share resource for the provided customer.
     * - Uses resource data from provided ResourceShareRequestTransfer::ResourceShareTransfer object.
     * - Sets UUID in returned transfer if generation was successful.
     * - Sets `isSuccessful=true` if generation was successful, adds error messages otherwise.
     * - Does not create new UUID if the provided "resourceType" and "resourceData" pair exists for current customer.
     * - Applies corresponding ResourceShareResourceDataExpanderStrategyPluginInterface strategy plugin to expand resource data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer;

    /**
     * Specification:
     * - Returns with "isSuccess=false" and error message if resource is not found by provided uuid.
     * - Applies corresponding strategy using `ResourceShareActivatorStrategyPluginInterface` stack.
     * - Returns with "isLoginRequired=true" when strategy expects logged in customer.
     * - Returns with "isSuccess=true" on success or with error messages otherwise.
     * - Applies corresponding ResourceShareResourceDataExpanderStrategyPluginInterface strategy plugin to expand resource data.
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
     * - Retrieves the share resource by the defined ResourceShareTransfer properties.
     * - Considers "resourceType", "resourceData" and "customerReference" values from ResourceShareTransfer.
     * - Returns "isSuccess=true" on success and error message otherwise.
     * - Applies corresponding ResourceShareResourceDataExpanderStrategyPluginInterface strategy plugin to expand resource data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function getResourceShare(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer;
}
