<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

/**
 * Provides extension capabilities for actions that should be executed after a MerchantRelationship is deleted.
 */
interface MerchantRelationshipPostDeletePluginInterface
{
    /**
     * Specification:
     * - Executes after a MerchantRelationship is deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function execute(MerchantRelationshipTransfer $merchantRelationshipTransfer): void;
}
