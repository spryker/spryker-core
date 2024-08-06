<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAppMerchantPortalGui\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantAppMerchantPortalGui\Communication\MerchantAppMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantAppMerchantPortalGui\MerchantAppMerchantPortalGuiConfig getConfig()
 */
class MerchantAppAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_CREATE}
     *
     * @var int
     */
    protected const OPERATION_MASK_CREATE = 0b10;

    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_UPDATE}
     *
     * @var int
     */
    protected const OPERATION_MASK_UPDATE = 0b100;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with entities related to MerchantApp module functionality.
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
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\KernelApp\Persistence\SpyAppConfig',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\KernelApp\Persistence\SpyAppConfig')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboarding',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboarding')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatus',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatus')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ | static::OPERATION_MASK_CREATE),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCache',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCache')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_UPDATE | static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
