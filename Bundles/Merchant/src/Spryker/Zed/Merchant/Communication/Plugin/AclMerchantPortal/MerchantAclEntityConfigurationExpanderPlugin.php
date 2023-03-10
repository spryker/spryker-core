<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 * @method \Spryker\Zed\Merchant\Communication\MerchantCommunicationFactory getFactory()
 */
class MerchantAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_CRUD}
     *
     * @var int
     */
    protected const OPERATION_MASK_CRUD = 0b1111;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant composite data.
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
                'Orm\Zed\Merchant\Persistence\SpyMerchant',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant')
                    ->setHasSegmentTable(true)
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CRUD),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Merchant\Persistence\SpyMerchantStore',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchantStore')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'))
                    ->setIsSubEntity(true),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
