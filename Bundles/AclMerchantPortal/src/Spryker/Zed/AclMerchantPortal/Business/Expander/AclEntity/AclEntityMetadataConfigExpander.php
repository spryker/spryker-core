<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Expander\AclEntity;

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
use Orm\Zed\Comment\Persistence\SpyCommentThread;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddress;
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
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationLastExportedVersion;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationStore;
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
use Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundle;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearch;
use Orm\Zed\ProductValidity\Persistence\SpyProductValidity;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressHistory;
use Orm\Zed\Sales\Persistence\SpySalesOrderComment;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundle;
use Orm\Zed\SalesConfigurableBundle\Persistence\SpySalesOrderConfiguredBundleItem;
use Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoice;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThreshold;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdTaxSet;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdType;
use Orm\Zed\SalesProductConfiguration\Persistence\SpySalesOrderItemConfiguration;
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
    public function expandAclEntityMetadataConfigWithMerchantProductComposite(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyAvailability::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyAvailability::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyAvailabilityAbstract::class)
                )
                ->setIsSubEntity(true)
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
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

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductImageSetToProductImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductImageSetToProductImage::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductImageSet::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductImage::class)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductImageSet::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductImageSet::class)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductLocalizedAttributes::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductLocalizedAttributes::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProduct::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductAbstractLocalizedAttributes::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductAbstractLocalizedAttributes::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductSearch::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductSearch::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProduct::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductValidity::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductValidity::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProduct::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductCategory::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductCategory::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProduct::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProduct::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyStockProduct::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStockProduct::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyStock::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductAbstract::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductAbstract::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantProductAbstract::class)
                )
                ->setIsSubEntity(false)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyMerchantProductAbstract::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantProductAbstract::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyUser::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyUser::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantUser::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
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

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
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
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyMerchantProfileAddress::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantProfileAddress::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantProfile::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyMerchantProfile::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantProfile::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyMerchantStock::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantStock::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyMerchantStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantStore::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
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
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyPriceProductOffer::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceProductOffer::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductOffer::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductOfferStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOfferStore::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductOffer::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductOfferValidity::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOfferValidity::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductOffer::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
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

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCategoryAttribute::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryAttribute::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategory::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCategoryClosureTable::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryClosureTable::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategoryNode::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCategoryNode::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryNode::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategory::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCategoryStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryStore::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategory::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyStockStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStockStore::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyStock::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductOfferStock::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOfferStock::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyStock::class)
                )
                ->setIsSubEntity(true)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyStock::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStock::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantStock::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductAbstractStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductAbstractStore::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAbstract::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCategoryImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryImage::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategoryImageSetToCategoryImage::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCategoryImageSetToCategoryImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryImageSetToCategoryImage::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategoryImageSet::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCategoryImageSet::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryImageSet::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCategory::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductManagementAttributeValue::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductManagementAttributeValue::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductManagementAttribute::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductManagementAttribute::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductManagementAttribute::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductAttributeKey::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyPriceProductDefault::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceProductDefault::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyPriceProductStore::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyPriceProductStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceProductStore::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyPriceProduct::class)
                )
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyTaxRate::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyTaxRate::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyCountry::class)
                )
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyGlossaryTranslation::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyGlossaryTranslation::class)
                ->setIsSubEntity(true)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyGlossaryKey::class)
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
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyStore::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyStore::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCurrency::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCurrency::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCountry::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCountry::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyLocale::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyLocale::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCategory::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategory::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyCategoryTemplate::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyCategoryTemplate::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyTaxSet::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyTaxSet::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );

        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyGlossaryKey::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyGlossaryKey::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_CRUD)
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductAttributeKey::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductAttributeKey::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyProductManagementAttributeValueTranslation::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductManagementAttributeValue::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyPriceProduct::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceProduct::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclEntityMetadataConfigTransfer->getAclEntityMetadataCollectionOrFail()->addAclEntityMetadata(
            SpyPriceType::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyPriceType::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );

        return $aclEntityMetadataConfigTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntityMetadataConfigTransfer
     */
    public function expandAclEntityMetadataConfigWithWhitelist(
        AclEntityMetadataConfigTransfer $aclEntityMetadataConfigTransfer
    ): AclEntityMetadataConfigTransfer {
        $aclEntityMetadataConfigTransfer
            ->addAclEntityWhitelistItem(SpyUser::class)
            ->addAclEntityWhitelistItem(SpyUrl::class)
            ->addAclEntityWhitelistItem(SpyAclRole::class)
            ->addAclEntityWhitelistItem(SpyAclGroup::class)
            ->addAclEntityWhitelistItem(SpyAclRule::class)
            ->addAclEntityWhitelistItem(SpyAclGroupsHasRoles::class)
            ->addAclEntityWhitelistItem(SpyAclUserHasGroup::class)
            ->addAclEntityWhitelistItem(SpyAclEntitySegment::class)
            ->addAclEntityWhitelistItem(SpyAclEntityRule::class)
            ->addAclEntityWhitelistItem(SpyEventBehaviorEntityChange::class)
            ->addAclEntityWhitelistItem(SpyUrlRedirect::class)
            ->addAclEntityWhitelistItem(SpyResetPassword::class)
            ->addAclEntityWhitelistItem(SpyCmsBlock::class)
            ->addAclEntityWhitelistItem(SpySalesShipment::class)
            ->addAclEntityWhitelistItem(SpyShipmentCarrier::class)
            ->addAclEntityWhitelistItem(SpyShipmentMethod::class)
            ->addAclEntityWhitelistItem(SpyShipmentMethodPrice::class)
            ->addAclEntityWhitelistItem(SpyShipmentMethodStore::class)
            ->addAclEntityWhitelistItem(SpySalesPayment::class)
            ->addAclEntityWhitelistItem(SpyCommentThread::class)
            ->addAclEntityWhitelistItem(SpySalesDiscount::class)
            ->addAclEntityWhitelistItem(SpySalesPaymentMethodType::class)
            ->addAclEntityWhitelistItem(SpyCustomer::class)
            ->addAclEntityWhitelistItem(SpyCustomerAddress::class)
            ->addAclEntityWhitelistItem(SpySalesExpense::class)
            ->addAclEntityWhitelistItem(SpySalesOrderConfiguredBundle::class)
            ->addAclEntityWhitelistItem(SpySalesOrderConfiguredBundleItem::class)
            ->addAclEntityWhitelistItem(SpyOmsOrderItemState::class)
            ->addAclEntityWhitelistItem(SpyOmsOrderItemStateHistory::class)
            ->addAclEntityWhitelistItem(SpyOmsTransitionLog::class)
            ->addAclEntityWhitelistItem(SpyOmsOrderProcess::class)
            ->addAclEntityWhitelistItem(SpyOmsStateMachineLock::class)
            ->addAclEntityWhitelistItem(SpyOmsProductOfferReservation::class)
            ->addAclEntityWhitelistItem(SpyOmsProductReservation::class)
            ->addAclEntityWhitelistItem(SpyOmsProductReservationChangeVersion::class)
            ->addAclEntityWhitelistItem(SpyOmsProductReservationLastExportedVersion::class)
            ->addAclEntityWhitelistItem(SpyOmsProductReservationStore::class)
            ->addAclEntityWhitelistItem(SpySalesOrderAddress::class)
            ->addAclEntityWhitelistItem(SpySalesOrderItemBundle::class)
            ->addAclEntityWhitelistItem(SpySalesOrderAddressHistory::class)
            ->addAclEntityWhitelistItem(SpyStateMachineLock::class)
            ->addAclEntityWhitelistItem(SpyStateMachineTransitionLog::class)
            ->addAclEntityWhitelistItem(SpyStateMachineItemState::class)
            ->addAclEntityWhitelistItem(SpyStateMachineItemStateHistory::class)
            ->addAclEntityWhitelistItem(SpyStateMachineProcess::class)
            ->addAclEntityWhitelistItem(SpyMerchantSalesOrderTotals::class)
            ->addAclEntityWhitelistItem(SpySalesOrderTotals::class)
            ->addAclEntityWhitelistItem(SpySalesOrderComment::class)
            ->addAclEntityWhitelistItem(SpySalesOrderInvoice::class)
            ->addAclEntityWhitelistItem(SpySalesOrderThreshold::class)
            ->addAclEntityWhitelistItem(SpySalesOrderThresholdTaxSet::class)
            ->addAclEntityWhitelistItem(SpySalesOrderThresholdType::class)
            ->addAclEntityWhitelistItem(SpyMerchantSalesOrderItem::class)
            ->addAclEntityWhitelistItem(SpySalesOrderItemOption::class)
            ->addAclEntityWhitelistItem(SpySalesOrderItemConfiguration::class)
            ->addAclEntityWhitelistItem(SpySalesOrderItemGiftCard::class)
            ->addAclEntityWhitelistItem(SpySalesOrderItemMetadata::class)
            ->addAclEntityWhitelistItem(SpySalesOrderItem::class)
            ->addAclEntityWhitelistItem(SpySalesOrder::class)
            ->addAclEntityWhitelistItem(SpyMerchantSalesOrder::class)
            ->addAclEntityWhitelistItem(SpyRefund::class);

        return $aclEntityMetadataConfigTransfer;
    }
}
