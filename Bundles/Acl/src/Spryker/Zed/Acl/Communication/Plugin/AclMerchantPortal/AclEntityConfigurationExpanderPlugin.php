<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Acl\Business\AclFacadeInterface getFacade()
 * @method \Spryker\Zed\Acl\AclConfig getConfig()
 * @method \Spryker\Zed\Acl\Persistence\AclQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Acl\Communication\AclCommunicationFactory getFactory()
 */
class AclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with ACL composite data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expand(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer
    {
        return $aclEntityMetadataConfigTransfer
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclRole')
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclGroup')
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclRule')
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclGroupsHasRoles')
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclUserHasGroup');
    }
}
