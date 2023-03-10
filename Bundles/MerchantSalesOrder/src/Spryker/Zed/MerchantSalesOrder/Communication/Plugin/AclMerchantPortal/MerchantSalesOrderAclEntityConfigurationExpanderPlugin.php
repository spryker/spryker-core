<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderConfig getConfig()
 */
class MerchantSalesOrderAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with merchant sales order composite data.
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
                'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder'))
                    ->setIsSubEntity(false),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesOrderItem',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder')
                    ->setParent(
                        (new AclEntityParentMetadataTransfer())
                            ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant')
                            ->setConnection((new AclEntityParentConnectionMetadataTransfer())->setReference('merchant_reference')->setReferencedColumn('merchant_reference')),
                    ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
