<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntityExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;

/**
 * Implement this interface to expand `AclEntityMetadataCollection` transfer object.
 */
interface AclEntityMetadataConfigExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `AclEntityMetadataConfig` transfer object with additional data.
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
