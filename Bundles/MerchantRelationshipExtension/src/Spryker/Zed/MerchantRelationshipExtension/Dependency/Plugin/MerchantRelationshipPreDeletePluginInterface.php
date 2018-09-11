<?php

namespace Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipPreDeletePluginInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Returns true or false based on of the merchant relationship can be deleted
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer;
     *
     * @return bool
     */
    public function check(MerchantRelationshipTransfer $merchantRelationshipTransfer): bool;
}