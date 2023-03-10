<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class DiscountAclEntityConfigurationExpanderPlugin extends AbstractPlugin implements AclEntityConfigurationExpanderPluginInterface
{
    /**
     * @uses {@link \Spryker\Shared\AclEntity\AclEntityConstants::OPERATION_MASK_READ}
     *
     * @var int
     */
    protected const OPERATION_MASK_READ = 0b1;

    /**
     * {@inheritDoc}
     * - Expands provided `AclEntityMetadataConfig` transfer object with discount composite data.
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
                'Orm\Zed\Discount\Persistence\SpyDiscount',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscount')
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Discount\Persistence\SpyDiscountAmount',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountAmount')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscount'))
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Discount\Persistence\SpyDiscountStore',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountStore')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscount'))
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscount'))
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Discount\Persistence\SpyDiscountVoucher',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountVoucher')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool'))
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesDiscount',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesDiscount')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'))
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            )
            ->addAclEntityMetadata(
                'Orm\Zed\Sales\Persistence\SpySalesDiscountCode',
                (new AclEntityMetadataTransfer())
                    ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesDiscountCode')
                    ->setParent((new AclEntityParentMetadataTransfer())->setEntityName('Orm\Zed\Sales\Persistence\SpySalesDiscount'))
                    ->setDefaultGlobalOperationMask(static::OPERATION_MASK_READ),
            );

        return $aclEntityMetadataConfigTransfer;
    }
}
