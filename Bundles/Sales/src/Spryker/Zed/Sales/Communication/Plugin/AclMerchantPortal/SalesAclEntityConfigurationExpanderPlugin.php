<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 */
class SalesAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with sales composite data.
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
            ->addAclEntityAllowListItem('Orm\Zed\Sales\Persistence\SpySalesOrderAddress')
            ->getAclEntityMetadataCollectionOrFail()
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesDiscount',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesDiscount')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesExpense',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesExpense')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesOrderTotals',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderTotals')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesOrderAddress',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderAddress')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesOrderComment',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderComment')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesOrder',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Customer\Persistence\SpyCustomer',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Customer\Persistence\SpyCustomer')
                    ->setParent(
                        (new AclEntityParentMetadataTransfer())
                            ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder')
                            ->setConnection((new AclEntityParentConnectionMetadataTransfer())->setReference('customer_reference')->setReferencedColumn('customer_reference')),
                    ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
