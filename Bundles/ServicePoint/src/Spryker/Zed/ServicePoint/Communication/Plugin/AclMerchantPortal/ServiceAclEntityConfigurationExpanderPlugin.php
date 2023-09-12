<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePoint\ServicePointConfig getConfig()
 */
class ServiceAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with service point composite data.
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
                'Orm\Zed\ServicePoint\Persistence\SpyService',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\ServicePoint\Persistence\SpyService')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )->addAclEntityMetadata(
                'Orm\Zed\ServicePoint\Persistence\SpyServicePoint',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\ServicePoint\Persistence\SpyServicePoint')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )->addAclEntityMetadata(
                'Orm\Zed\ServicePoint\Persistence\SpyServiceType',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\ServicePoint\Persistence\SpyServiceType')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
