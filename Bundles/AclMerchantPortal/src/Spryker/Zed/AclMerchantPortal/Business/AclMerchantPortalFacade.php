<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AclMerchantPortal\Business\AclMerchantPortalBusinessFactory getFactory()
 */
class AclMerchantPortalFacade extends AbstractFacade implements AclMerchantPortalFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createMerchantAclData(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getFactory()->createAclMerchantPortalWriter()->createMerchantAclData($merchantTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function createMerchantUserAclData(MerchantUserTransfer $merchantUserTransfer): MerchantUserTransfer
    {
        return $this->getFactory()->createAclMerchantPortalWriter()->createMerchantUserAclData($merchantUserTransfer);
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
    public function expandAclEntityMetadataConfigWithMerchantProductComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        return $this->getFactory()
            ->createAclEntityMetadataConfigExpander()
            ->expandAclEntityMetadataConfigWithMerchantProductComposite($aclEntityMetadataConfigTransfer);
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
    public function expandAclEntityMetadataConfigWithMerchantComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        return $this->getFactory()
            ->createAclEntityMetadataConfigExpander()
            ->expandAclEntityMetadataConfigWithMerchantComposite($aclEntityMetadataConfigTransfer);
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
    public function expandAclEntityMetadataConfigWithProductOfferComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        return $this->getFactory()
            ->createAclEntityMetadataConfigExpander()
            ->expandAclEntityMetadataConfigWithProductOfferComposite($aclEntityMetadataConfigTransfer);
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
    public function expandAclEntityMetadataConfigWithMerchantReadGlobalEntities(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        return $this->getFactory()
            ->createAclEntityMetadataConfigExpander()
            ->expandAclEntityMetadataConfigWithMerchantReadGlobalEntities($aclEntityMetadataConfigTransfer);
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
    public function expandAclEntityMetadataConfigWithWhitelist(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        return $this->getFactory()
            ->createAclEntityMetadataConfigExpander()
            ->expandAclEntityMetadataConfigWithWhitelist($aclEntityMetadataConfigTransfer);
    }
}
