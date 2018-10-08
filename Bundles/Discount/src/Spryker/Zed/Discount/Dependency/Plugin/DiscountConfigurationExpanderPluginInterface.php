<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;

interface DiscountConfigurationExpanderPluginInterface
{
    /**
     *  Specification:
     *   - This plugin is used to add additional data to DiscountConfigurationTransfer, which is then mapped to Zed discount form.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function expand(DiscountConfiguratorTransfer $discountConfiguratorTransfer);
}
