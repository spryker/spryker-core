<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntityDummyProduct\Communication;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AclEntityDummyProduct\Business\AclEntityDummyProductFacadeInterface getFacade()
 */
class DummyProductAclEntityMetadataConfigExpanderPlugin extends AbstractPlugin implements AclEntityMetadataConfigExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with product and store relation.
     * - Expands provided `AclEntityMetadataConfig` transfer object with product composite data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expand(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer = $this->getFacade()
            ->expandAclEntityMetadataConfigWithProductStoreRelation($aclEntityMetadataConfigTransfer);

        $aclEntityMetadataConfigTransfer = $this->getFacade()
            ->expandAclEntityMetadataConfigWithProductCompositeRelation($aclEntityMetadataConfigTransfer);

        return $aclEntityMetadataConfigTransfer;
    }
}
