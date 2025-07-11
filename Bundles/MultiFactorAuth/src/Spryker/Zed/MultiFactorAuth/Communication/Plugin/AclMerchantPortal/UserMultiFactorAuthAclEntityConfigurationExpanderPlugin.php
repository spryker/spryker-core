<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Orm\Zed\MultiFactorAuth\Persistence\SpyUserMultiFactorAuth;
use Orm\Zed\MultiFactorAuth\Persistence\SpyUserMultiFactorAuthCodes;
use Orm\Zed\MultiFactorAuth\Persistence\SpyUserMultiFactorAuthCodesAttempts;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 */
class UserMultiFactorAuthAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
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
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_CREATE}
     *
     * @var int
     */
    protected const OPERATION_MASK_CREATE = 0b10;

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
            ->addAclEntityAllowListItem(SpyUserMultiFactorAuth::class)
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                SpyUserMultiFactorAuth::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyUserMultiFactorAuth::class)
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ | static::OPERATION_MASK_UPDATE)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName(SpyUserMultiFactorAuth::class)),
            )
            ->addAclEntityMetadata(
                SpyUserMultiFactorAuthCodes::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyUserMultiFactorAuthCodes::class)
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ | static::OPERATION_MASK_UPDATE)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName(SpyUserMultiFactorAuth::class)),
            )
            ->addAclEntityMetadata(
                SpyUserMultiFactorAuthCodesAttempts::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyUserMultiFactorAuthCodesAttempts::class)
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ | static::OPERATION_MASK_UPDATE)
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName(SpyUserMultiFactorAuthCodes::class)),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
