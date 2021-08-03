<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntityDummyProduct\Business;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;

interface AclEntityDummyProductFacadeInterface
{
    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` transfer object with product and store relation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithProductStoreRelation(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;

    /**
     * Specification:
     * - Expands provided `AclEntityMetadataConfig` transfer object with composite data for product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithProductCompositeRelation(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer;
}
