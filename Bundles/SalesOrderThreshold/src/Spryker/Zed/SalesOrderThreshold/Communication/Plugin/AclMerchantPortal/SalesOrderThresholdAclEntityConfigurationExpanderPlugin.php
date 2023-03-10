<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig getConfig()
 */
class SalesOrderThresholdAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with sales order threshold composite data.
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
                'Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdTaxSet',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdTaxSet')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'))
                    ->setIsSubEntity(true),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
