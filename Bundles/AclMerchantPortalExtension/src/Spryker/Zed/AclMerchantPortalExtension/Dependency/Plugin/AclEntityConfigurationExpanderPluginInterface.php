<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;

/**
 * Provides capabilities to expand `AclEntityMetadataConfig`.
 *
 * Use this plugin if some default/predefined `AclEntityMetadataConfig` need to be expanded.
 */
interface AclEntityConfigurationExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` with different composite data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expand(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;
}
