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
use Orm\Zed\Acl\Persistence\SpyAclGroup;
use Orm\Zed\Acl\Persistence\SpyAclGroupsHasRoles;
use Orm\Zed\Acl\Persistence\SpyAclRole;
use Orm\Zed\Acl\Persistence\SpyAclRule;
use Orm\Zed\Acl\Persistence\SpyAclUserHasGroup;
use Orm\Zed\AclEntity\Persistence\SpyAclEntityRule;
use Orm\Zed\AclEntity\Persistence\SpyAclEntitySegment;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTable;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryStore;
use Orm\Zed\Category\Persistence\SpyCategoryTemplate;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Orm\Zed\Comment\Persistence\SpyCommentThread;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\Merchant\Persistence\SpyMerchantStore;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistory;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLog;
use Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefault;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceType;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProductAbstractStore;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearch;
use Orm\Zed\ProductValidity\Persistence\SpyProductValidity;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderComment;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoice;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdTaxSet;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodPrice;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodStore;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemState;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistory;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineLock;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcess;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Orm\Zed\Stock\Persistence\SpyStockStore;
use Orm\Zed\Store\Persistence\SpyStore;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Orm\Zed\User\Persistence\SpyUser;
use Orm\Zed\UserPasswordReset\Persistence\SpyResetPassword;
use Spryker\Shared\AclEntity\AclEntityConstants;

class AclEntityMetadataConfigExpander implements AclEntityMetadataConfigExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithMerchantOrderComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        if (!$aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection()) {
            $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
        }
        /** @var \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer */
        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesDiscount::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesDiscount::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrderItem::class)
                )
                ->setIsSubEntity(true)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyOmsTransitionLog::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyOmsTransitionLog::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrderItem::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesShipment::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesShipment::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
                ->setIsSubEntity(true)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesPayment::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesPayment::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
                ->setIsSubEntity(true)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesExpense::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesExpense::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantSalesOrderTotals::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantSalesOrderTotals::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantSalesOrder::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderTotals::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderTotals::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderComment::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderComment::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderInvoice::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderInvoice::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderThreshold::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderThreshold::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderThresholdTaxSet::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderThresholdTaxSet::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderThresholdType::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderThresholdType::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantSalesOrderItem::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantSalesOrderItem::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantSalesOrder::class)
                )
            ->setIsSubEntity(false)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderItemOption::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderItemOption::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrderItem::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderItemGiftCard::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderItemGiftCard::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrderItem::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderItemMetadata::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderItemMetadata::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrderItem::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrderItem::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrderItem::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantSalesOrderItem::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesOrder::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesOrder::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrderItem::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyRefund::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyRefund::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCustomer::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCustomer::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpySalesOrder::class)
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('customer_reference')
                                ->setReferencedColumn('customer_reference')
                        )
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantSalesOrder::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantSalesOrder::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('merchant_reference')
                                ->setReferencedColumn('merchant_reference')
                        )
                )
        );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithMerchantProductComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        if (!$aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection()) {
            $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
        }
        /** @var \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer */
        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyOmsProductReservation::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyOmsProductReservation::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProduct::class)
                    ->setConnection(
                        (new AclEntityParentConnectionMetadataTransfer())
                            ->setReference('sku')
                            ->setReferencedColumn('sku')
                    )
                )
            ->setIsSubEntity(true)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyAvailability::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyAvailability::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyAvailabilityAbstract::class)
                )
                ->setIsSubEntity(true)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyAvailabilityAbstract::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyAvailabilityAbstract::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class)
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('abstract_sku')
                                ->setReferencedColumn('sku')
                        )
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductImageSetToProductImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductImageSetToProductImage::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductImageSet::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductImage::class)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductImageSet::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductImageSet::class)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductLocalizedAttributes::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductLocalizedAttributes::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProduct::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductAbstractLocalizedAttributes::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductAbstractLocalizedAttributes::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductSearch::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductSearch::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProduct::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductValidity::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductValidity::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProduct::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductCategory::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductCategory::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProduct::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProduct::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyStockProduct::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStockProduct::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyStock::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductAbstract::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductAbstract::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantProductAbstract::class)
                )
                ->setIsSubEntity(false)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantProductAbstract::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantProductAbstract::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyUser::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyUser::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantUser::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantUser::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantUser::class)
                ->setHasSegmentTable(true)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchant::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchant::class)
                ->setHasSegmentTable(true)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_CRUD)
        );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithMerchantComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        if (!$aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection()) {
            $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
        }
        /** @var \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer */
        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantProfileAddress::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantProfileAddress::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantProfile::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantProfile::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantProfile::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantStock::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantStock::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantStore::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantCategory::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantCategory::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
                ->setIsSubEntity(true)
        );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithProductOfferComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        if (!$aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection()) {
            $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
        }
        /** @var \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer */
        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyPriceProductOffer::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceProductOffer::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductOffer::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductOfferStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOfferStore::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductOffer::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductOfferValidity::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOfferValidity::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductOffer::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductOffer::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOffer::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('merchant_reference')
                                ->setReferencedColumn('merchant_reference')
                        )
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCategoryAttribute::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryAttribute::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategory::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCategoryClosureTable::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryClosureTable::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategoryNode::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCategoryNode::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryNode::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategory::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCategoryStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryStore::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategory::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyStockStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStockStore::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyStock::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductOfferStock::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOfferStock::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyStock::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyStock::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStock::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantStock::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductAbstractStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductAbstractStore::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCategoryImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryImage::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategoryImageSetToCategoryImage::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCategoryImageSetToCategoryImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryImageSetToCategoryImage::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategoryImageSet::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCategoryImageSet::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryImageSet::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategory::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductManagementAttributeValue::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductManagementAttributeValue::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductManagementAttribute::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductManagementAttribute::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductManagementAttribute::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAttributeKey::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyPriceProductDefault::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceProductDefault::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyPriceProductStore::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyPriceProductStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceProductStore::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyPriceProduct::class)
                )
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyTaxRate::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyTaxRate::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCountry::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyGlossaryTranslation::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyGlossaryTranslation::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyGlossaryKey::class)
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyOmsProductOfferReservation::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyOmsProductOfferReservation::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductOffer::class)
                    ->setConnection(
                        (new AclEntityParentConnectionMetadataTransfer())
                            ->setReference('product_offer_reference')
                            ->setReferencedColumn('product_offer_reference')
                    )
                )
        );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithMerchantReadGlobalEntities(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        if (!$aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection()) {
            $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection(new AclEntityMetadataCollectionTransfer());
        }
        /** @var \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer */
        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStore::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCurrency::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCurrency::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCountry::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCountry::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyLocale::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyLocale::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCategory::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategory::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCategoryTemplate::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryTemplate::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyTaxSet::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyTaxSet::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyGlossaryKey::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyGlossaryKey::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_CRUD)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductAttributeKey::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductAttributeKey::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductManagementAttributeValueTranslation::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductManagementAttributeValueTranslation::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyPriceProduct::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceProduct::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyPriceType::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceType::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyShipmentCarrier::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyShipmentCarrier::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyShipmentMethod::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyShipmentMethod::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyShipmentMethodPrice::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyShipmentMethodPrice::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyShipmentMethodStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyShipmentMethodStore::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpySalesPaymentMethodType::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpySalesPaymentMethodType::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyOmsOrderProcess::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyOmsOrderProcess::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCommentThread::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCommentThread::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyOmsStateMachineLock::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyOmsStateMachineLock::class)
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_DELETE
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyOmsOrderItemState::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyOmsOrderItemState::class)
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyOmsOrderItemStateHistory::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyOmsOrderItemStateHistory::class)
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyStateMachineItemState::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStateMachineItemState::class)
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyStateMachineItemStateHistory::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStateMachineItemStateHistory::class)
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyUrlRedirect::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyUrlRedirect::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyResetPassword::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyResetPassword::class)
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCmsBlock::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCmsBlock::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyUrl::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyUrl::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyStateMachineProcess::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStateMachineProcess::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyStateMachineTransitionLog::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStateMachineTransitionLog::class)
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductOptionGroup::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOptionGroup::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductOptionValue::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOptionValue::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyEventBehaviorEntityChange::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyEventBehaviorEntityChange::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyStateMachineLock::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStateMachineLock::class)
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_DELETE
                )
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCmsBlockTemplate::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCmsBlockTemplate::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyCmsBlockGlossaryKeyMapping::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCmsBlockGlossaryKeyMapping::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyOmsProductReservationChangeVersion::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyOmsProductReservationChangeVersion::class)
                ->setDefaultGlobalOperationMask(
                    AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ
                )
        );

        return $aclEntityMetadataConfigTransfer;
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
            ->addAclEntityAllowListItem(SpyUser::class)
            ->addAclEntityAllowListItem(SpyUrl::class)
            ->addAclEntityAllowListItem(SpyAclRole::class)
            ->addAclEntityAllowListItem(SpyAclGroup::class)
            ->addAclEntityAllowListItem(SpyAclRule::class)
            ->addAclEntityAllowListItem(SpyAclGroupsHasRoles::class)
            ->addAclEntityAllowListItem(SpyAclUserHasGroup::class)
            ->addAclEntityAllowListItem(SpyAclEntitySegment::class)
            ->addAclEntityAllowListItem(SpyAclEntityRule::class)
            // TODO: SpySalesOrder.fkSalesOrderAddressBilling, SpySalesOrder.fkSalesOrderAddressShipping
            ->addAclEntityAllowListItem(SpySalesOrderAddress::class)
            ->addAclEntityAllowListItem(SpyEventBehaviorEntityChange::class);

        return $aclEntityMetadataConfigTransfer;
    }
}
