<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantDashboardCardTransfer;

/**
 * Use this plugin interface to expand the Merchant Relationship dashboard card.
 */
interface MerchantRelationshipMerchantDashboardCardExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided `MerchantDashboardCardTransfer` properties.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantDashboardCardTransfer $merchantDashboardCardTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function expand(MerchantDashboardCardTransfer $merchantDashboardCardTransfer): MerchantDashboardCardTransfer;
}
