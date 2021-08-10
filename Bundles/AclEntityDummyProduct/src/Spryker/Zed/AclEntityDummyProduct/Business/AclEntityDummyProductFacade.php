<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntityDummyProduct\Business;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AclEntityDummyProduct\Business\AclEntityDummyProductBusinessFactory getFactory()
 */
class AclEntityDummyProductFacade extends AbstractFacade implements AclEntityDummyProductFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithProductStoreRelation(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        return $this->getFactory()
            ->createAclEntityMetadataConfigExpander()
            ->expandAclEntityMetadataConfigWithProductStoreRelation($aclEntityMetadataConfigTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithProductCompositeRelation(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        return $this->getFactory()
            ->createAclEntityMetadataConfigExpander()
            ->expandAclEntityMetadataConfigWithProductComposite($aclEntityMetadataConfigTransfer);
    }
}
