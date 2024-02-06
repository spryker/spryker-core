<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 * @method \Spryker\Zed\MerchantUser\Communication\MerchantUserCommunicationFactory getFactory()
 */
class MerchantUserAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_UPDATE}
     *
     * @var int
     */
    protected const OPERATION_MASK_UPDATE = 0b100;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant user composite data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expand(AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer): AclEntityMetadataConfigTransfer
    {
        $aclEntityMetadataConfigTransfer
            ->addAclEntityAllowListItem('Orm\Zed\User\Persistence\SpyUser')
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\User\Persistence\SpyUser',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\User\Persistence\SpyUser')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ | static::OPERATION_MASK_UPDATE)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantUser\Persistence\SpyMerchantUser')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantUser\Persistence\SpyMerchantUser',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantUser\Persistence\SpyMerchantUser')
                    ->setHasSegmentTable(true)
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant')),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
