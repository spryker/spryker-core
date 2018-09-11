<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantRelationshipDeleteResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipPreDeletePluginInterface
{
    /**
     * @api
     *
     * Specification:
     * - This plugin is executed before a MerchantRelationship is deleted
     * - Returns a MerchantRelationshipDeleteResponseTransfer which indicates if the MerchantRelationship can be deleted or not
     * - If the MerchantRelationship cannot be deleted the MerchantRelationshipDeleteResponseTransfer will contain all related error messages
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipDeleteResponseTransfer $merchantRelationshipDeleteResponseTransfer
     */
    public function execute(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipDeleteResponseTransfer;
}
