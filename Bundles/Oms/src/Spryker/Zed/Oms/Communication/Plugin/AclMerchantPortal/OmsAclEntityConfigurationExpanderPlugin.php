<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 */
class OmsAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
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
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_DELETE}
     *
     * @var int
     */
    protected const OPERATION_MASK_DELETE = 0b1000;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with oms composite data.
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
                'Orm\Zed\Oms\Persistence\SpyOmsTransitionLog',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsTransitionLog')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem')),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Oms\Persistence\SpyOmsProductReservation',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsProductReservation')
                    ->setParent(
                        (new AclEntityParentMetadataTransfer())
                            ->setEntityName('Orm\Zed\Product\Persistence\SpyProduct')
                            ->setConnection((new AclEntityParentConnectionMetadataTransfer())->setReference('sku')->setReferencedColumn('sku')),
                    )
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Oms\Persistence\SpyOmsOrderProcess',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsOrderProcess')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ | static::OPERATION_MASK_DELETE),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Oms\Persistence\SpyOmsOrderItemState',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsOrderItemState')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_CREATE | static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
