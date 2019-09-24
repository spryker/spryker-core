<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipPostCreatePluginInterface
{
    /**
     * Specification:
     * - This plugin is executed after a MerchantRelationship creation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function execute(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer;
}
