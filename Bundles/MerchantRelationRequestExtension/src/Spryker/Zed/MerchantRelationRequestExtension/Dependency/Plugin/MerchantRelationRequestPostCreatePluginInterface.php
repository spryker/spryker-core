<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;

/**
 * Provides extension capabilities for actions that should be executed after a MerchantRelationRequest is created.
 */
interface MerchantRelationRequestPostCreatePluginInterface
{
    /**
     * Specification:
     * - Executes after the merchant relation request is created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    public function postCreate(MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer): void;
}
