<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLog;
use Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceType;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Orm\Zed\Store\Persistence\SpyStore;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Orm\Zed\User\Persistence\SpyUser;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class AclMerchantPortalConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\Acl\AclConstants::VALIDATOR_WILDCARD
     *
     * @var string
     */
    protected const RULE_VALIDATOR_WILDCARD = '*';

    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ALLOW
     *
     * @var string
     */
    protected const RULE_TYPE_ALLOW = 'allow';

    /**
     * @var string
     */
    protected const MERCHANT_ACL_REFERENCE_PREFIX = '__MERCHANT_';

    /**
     * @var string
     */
    protected const ACL_ROLE_PRODUCT_VIEWER_NAME = 'Product Viewer for Offer creation';

    /**
     * @var string
     */
    protected const ACL_ROLE_PRODUCT_VIEWER_REFERENCE = 'product-viewer-for-offer-creation';

    /**
     * Specification:
     * - Defines set of AclRules to assigned for merchant-specific AclRole.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\RuleTransfer>
     */
    public function getMerchantAclRoleRules(): array
    {
        $bundleNames = [
            'dashboard-merchant-portal-gui',
            'merchant-profile-merchant-portal-gui',
            'product-offer-merchant-portal-gui',
            'product-merchant-portal-gui',
            'sales-merchant-portal-gui',
            'dummy-merchant-portal-gui',
        ];

        $ruleTransfers = [];

        foreach ($bundleNames as $bundleName) {
            $ruleTransfers[] = (new RuleTransfer())
                ->setBundle($bundleName)
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW);
        }

        return $ruleTransfers;
    }

    /**
     * Specification:
     * - Defines set of AclEntityRules to assigned for merchant-specific AclRole.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function getMerchantAclRoleEntityRules(): array
    {
        $aclEntityRuleTransfers = [];

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyProductOffer::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyProductAbstract::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyMerchant::class)
            ->setScope(AclEntityConstants::SCOPE_SEGMENT)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyMerchantSalesOrder::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyMerchantSalesOrderItem::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyMerchantProductAbstract::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyCurrency::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyCountry::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyStore::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyLocale::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpySalesOrder::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpySalesOrderTotals::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpySalesOrderItem::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyPriceProduct::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyPriceProductStore::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyProductImageSet::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyProductImage::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyPriceType::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyProductOfferStore::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_DELETE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyProductOfferStore::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyOmsProductOfferReservation::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyOmsProductReservation::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyOmsTransitionLog::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyUrlRedirect::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_DELETE | AclEntityConstants::OPERATION_MASK_UPDATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpySalesExpense::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyRefund::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_CREATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyCustomer::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ,
            );

        return $aclEntityRuleTransfers;
    }

    /**
     * Specification:
     * - The prefix will be used as a part of generated reference for SpyAclRole::reference, SpyAclGroup::reference, and SpyAclEntitySegment::reference.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantAclReferencePrefix(): string
    {
        return static::MERCHANT_ACL_REFERENCE_PREFIX;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\RuleTransfer>
     */
    public function getMerchantUserAclRoleRules(): array
    {
        $bundleNames = [
            'security-merchant-portal-gui',
            'user-merchant-portal-gui',
        ];

        $ruleTransfers = [];

        foreach ($bundleNames as $bundleName) {
            $ruleTransfers[] = (new RuleTransfer())
                ->setBundle($bundleName)
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW);
        }

        return $ruleTransfers;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function getMerchantUserAclRoleEntityRules(): array
    {
        $aclEntityRuleTransfers = [];

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyMerchantUser::class)
            ->setScope(AclEntityConstants::SCOPE_SEGMENT)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyUser::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        return $aclEntityRuleTransfers;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getMerchantUserAclReferencePrefix(): string
    {
        return '__MERCHANT_USER_';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductViewerForOfferCreationAclRoleName(): string
    {
        return static::ACL_ROLE_PRODUCT_VIEWER_NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductViewerForOfferCreationAclRoleReference(): string
    {
        return static::ACL_ROLE_PRODUCT_VIEWER_REFERENCE;
    }

    /**
     * Specification:
     * - Defines set of AclEntityRules to assign for ProductViewerForOfferCreation.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\AclEntityRuleTransfer> ;
     */
    public function getProductViewerForOfferCreationAclRoleEntityRules(): array
    {
        $aclEntityRuleTransfers = [];

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyProductAbstract::class)
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity(SpyProduct::class)
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        return $aclEntityRuleTransfers;
    }

    /**
     * Specification:
     * - Defines set of AclRules to assign for ProductViewerForOfferCreation.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\RuleTransfer> ;
     */
    public function getProductViewerForOfferCreationAclRoleRules(): array
    {
        $ruleTransfers = [];

        $ruleTransfers[] = (new RuleTransfer())
            ->setBundle('product-offer-merchant-portal-gui')
            ->setController('product-list')
            ->setAction(static::RULE_VALIDATOR_WILDCARD)
            ->setType(static::RULE_TYPE_ALLOW);

        return $ruleTransfers;
    }
}
