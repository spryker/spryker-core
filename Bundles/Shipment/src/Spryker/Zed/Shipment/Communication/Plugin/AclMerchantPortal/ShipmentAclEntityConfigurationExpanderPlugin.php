<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 */
class ShipmentAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with shipment composite data.
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
                'Orm\Zed\Sales\Persistence\SpySalesShipment',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesShipment')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'))
                    ->setIsSubEntity(true),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Shipment\Persistence\SpyShipmentCarrier',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Shipment\Persistence\SpyShipmentCarrier')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Shipment\Persistence\SpyShipmentMethod',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Shipment\Persistence\SpyShipmentMethod')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
