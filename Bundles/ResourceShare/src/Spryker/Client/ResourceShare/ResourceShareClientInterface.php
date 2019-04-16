<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;

interface ResourceShareClientInterface
{
    /**
     * Specification:
     * - Makes a Zed-Request call to generate share resource for the provided customer.
     * - Uses resource data from provided ResourceShareRequestTransfer::ResourceShareTransfer.
     * - Sets UUID in returned transfer if generation was successful.
     * - Sets `isSuccessful=true` if generation was successful, adds error messages otherwise.
     * - Does not create new UUID if the provided "resourceType" and "resourceData" pair exists.
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
     * - Makes Zed-Request.
     * - Returns with "isSuccess=false" and error message if resource is not found by provided uuid.
     * - Applies corresponding strategy if "isSuccess=false" using `ResourceShareActivatorStrategyPluginInterface` stack.
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
}
