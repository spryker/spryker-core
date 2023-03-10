<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Expander\AclEntity;

use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Spryker\Shared\AclEntity\AclEntityConstants;

/**
 * @deprecated Use {@link \Spryker\Zed\AclMerchantPortal\Business\Expander\AclEntityConfigurationExpander} instead.
 */
class AclEntityMetadataConfigExpander implements AclEntityMetadataConfigExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfig(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        if (!$aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection()) {
            $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
        }
        /** @var \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer */
        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesDiscount',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesDiscount')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Oms\Persistence\SpyOmsTransitionLog',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsTransitionLog')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesShipment',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesShipment')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Payment\Persistence\SpySalesPayment',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Payment\Persistence\SpySalesPayment')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesExpense',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesExpense')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesOrderTotals',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderTotals')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesOrderAddress',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderAddress')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesOrderComment',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderComment')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoice',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoice')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdTaxSet',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdTaxSet')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder'),
                )
                ->setIsSubEntity(false),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesOrderItemOption',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItemOption')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesOrderItem',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesOrder',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrderItem'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Refund\Persistence\SpyRefund',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Refund\Persistence\SpyRefund')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Customer\Persistence\SpyCustomer',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Customer\Persistence\SpyCustomer')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder')
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('customer_reference')
                                ->setReferencedColumn('customer_reference'),
                        ),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant')
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('merchant_reference')
                                ->setReferencedColumn('merchant_reference'),
                        ),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Oms\Persistence\SpyOmsProductReservation',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsProductReservation')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProduct')
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('sku')
                                ->setReferencedColumn('sku'),
                        ),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Availability\Persistence\SpyAvailability',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Availability\Persistence\SpyAvailability')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract')
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('abstract_sku')
                                ->setReferencedColumn('sku'),
                        ),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\ProductImage\Persistence\SpyProductImageSet'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductImage\Persistence\SpyProductImage',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductImage\Persistence\SpyProductImage'),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductImage\Persistence\SpyProductImageSet',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductImage\Persistence\SpyProductImageSet'),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProduct'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductSearch\Persistence\SpyProductSearch',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductSearch\Persistence\SpyProductSearch')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProduct'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductValidity\Persistence\SpyProductValidity',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductValidity\Persistence\SpyProductValidity')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProduct'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductCategory\Persistence\SpyProductCategory',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductCategory\Persistence\SpyProductCategory')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Product\Persistence\SpyProduct',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Product\Persistence\SpyProduct')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Stock\Persistence\SpyStockProduct',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Stock\Persistence\SpyStockProduct')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Stock\Persistence\SpyStock'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Product\Persistence\SpyProductAbstract',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract'),
                )
                ->setIsSubEntity(false),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\User\Persistence\SpyUser',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\User\Persistence\SpyUser')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\MerchantUser\Persistence\SpyMerchantUser'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantUser\Persistence\SpyMerchantUser',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantUser\Persistence\SpyMerchantUser')
                ->setHasSegmentTable(true)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Merchant\Persistence\SpyMerchant',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant')
                ->setHasSegmentTable(true)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_CRUD),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantStock\Persistence\SpyMerchantStock',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantStock\Persistence\SpyMerchantStock')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Merchant\Persistence\SpyMerchantStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchantStore')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\ProductOffer\Persistence\SpyProductOffer'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\ProductOffer\Persistence\SpyProductOffer'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\ProductOffer\Persistence\SpyProductOffer'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductOffer\Persistence\SpyProductOffer',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductOffer\Persistence\SpyProductOffer')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant')
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('merchant_reference')
                                ->setReferencedColumn('merchant_reference'),
                        ),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Category\Persistence\SpyCategoryAttribute',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryAttribute')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Category\Persistence\SpyCategory'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Category\Persistence\SpyCategoryClosureTable',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryClosureTable')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryNode'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Category\Persistence\SpyCategoryNode',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryNode')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Category\Persistence\SpyCategory'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Category\Persistence\SpyCategoryStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryStore')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Category\Persistence\SpyCategory'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Stock\Persistence\SpyStockStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Stock\Persistence\SpyStockStore')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Stock\Persistence\SpyStock'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Stock\Persistence\SpyStock'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Stock\Persistence\SpyStock',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Stock\Persistence\SpyStock')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\MerchantStock\Persistence\SpyMerchantStock'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Product\Persistence\SpyProductAbstractStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstractStore')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAbstract'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\CategoryImage\Persistence\SpyCategoryImage',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImage')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Category\Persistence\SpyCategory'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAttributeKey'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\PriceProduct\Persistence\SpyPriceProduct'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Tax\Persistence\SpyTaxRate',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Tax\Persistence\SpyTaxRate')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Country\Persistence\SpyCountry'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation')
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Glossary\Persistence\SpyGlossaryKey'),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\ProductOffer\Persistence\SpyProductOffer')
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('product_offer_reference')
                                ->setReferencedColumn('product_offer_reference'),
                        ),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Merchant\Persistence\SpyMerchant'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationship')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Company\Persistence\SpyCompany',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Company\Persistence\SpyCompany')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit'),
                )
                ->setIsSubEntity(true),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Store\Persistence\SpyStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Store\Persistence\SpyStore')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Currency\Persistence\SpyCurrency',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Currency\Persistence\SpyCurrency')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Country\Persistence\SpyCountry',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Country\Persistence\SpyCountry')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Locale\Persistence\SpyLocale',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Locale\Persistence\SpyLocale')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Category\Persistence\SpyCategory',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Category\Persistence\SpyCategory')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Category\Persistence\SpyCategoryTemplate',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Category\Persistence\SpyCategoryTemplate')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Tax\Persistence\SpyTaxSet',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Tax\Persistence\SpyTaxSet')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Glossary\Persistence\SpyGlossaryKey',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Glossary\Persistence\SpyGlossaryKey')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_CRUD),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Product\Persistence\SpyProductAttributeKey',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Product\Persistence\SpyProductAttributeKey')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\PriceProduct\Persistence\SpyPriceProduct',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\PriceProduct\Persistence\SpyPriceProduct')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\PriceProduct\Persistence\SpyPriceType',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\PriceProduct\Persistence\SpyPriceType')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Shipment\Persistence\SpyShipmentCarrier',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Shipment\Persistence\SpyShipmentCarrier')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Shipment\Persistence\SpyShipmentMethod',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Shipment\Persistence\SpyShipmentMethod')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Oms\Persistence\SpyOmsOrderProcess',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsOrderProcess')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Comment\Persistence\SpyCommentThread',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Comment\Persistence\SpyCommentThread')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_DELETE,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Oms\Persistence\SpyOmsOrderItemState',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsOrderItemState')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Url\Persistence\SpyUrlRedirect',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Url\Persistence\SpyUrlRedirect')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlock',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\CmsBlock\Persistence\SpyCmsBlock')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Url\Persistence\SpyUrl',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Url\Persistence\SpyUrl')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\ProductOption\Persistence\SpyProductOptionValue',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\ProductOption\Persistence\SpyProductOptionValue')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\StateMachine\Persistence\SpyStateMachineLock',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\StateMachine\Persistence\SpyStateMachineLock')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_DELETE,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStore')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping')
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Discount\Persistence\SpyDiscount',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscount')
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Discount\Persistence\SpyDiscountAmount',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountAmount')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscount'),
                )
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Discount\Persistence\SpyDiscountStore',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountStore')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscount'),
                )
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscount'),
                )
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Discount\Persistence\SpyDiscountVoucher',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountVoucher')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool'),
                )
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Discount\Persistence\SpyDiscount'),
                )
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesDiscount',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesDiscount')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesOrder'),
                )
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_READ,
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            'Orm\Zed\Sales\Persistence\SpySalesDiscountCode',
            (new AclEntityMetadataTransfer())
                ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesDiscountCode')
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName('Orm\Zed\Sales\Persistence\SpySalesDiscount'),
                )
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_READ,
                ),
        );

        return $this->expandAclEntityMetadataConfigWithAllowList($aclEntityMetadataConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithAllowList(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->addAclEntityAllowListItem('Orm\Zed\User\Persistence\SpyUser')
            ->addAclEntityAllowListItem('Orm\Zed\Url\Persistence\SpyUrl')
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclRole')
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclGroup')
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclRule')
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclGroupsHasRoles')
            ->addAclEntityAllowListItem('Orm\Zed\Acl\Persistence\SpyAclUserHasGroup')
            ->addAclEntityAllowListItem('Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment')
            ->addAclEntityAllowListItem('Orm\Zed\AclEntity\Persistence\SpyAclEntityRule')
            ->addAclEntityAllowListItem('Orm\Zed\Sales\Persistence\SpySalesOrderAddress')
            ->addAclEntityAllowListItem('Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange');

        return $aclEntityMetadataConfigTransfer;
    }
}
