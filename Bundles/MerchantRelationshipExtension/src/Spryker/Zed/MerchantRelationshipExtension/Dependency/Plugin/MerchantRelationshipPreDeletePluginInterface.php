<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipPreDeletePluginInterface
{
    /**
     * Specification:
     * - This plugin is executed before a MerchantRelationship is deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function execute(MerchantRelationshipTransfer $merchantRelationshipTransfer): void;
}
