<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal;

use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\RuleTransfer;
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
     * @var bool
     */
    protected const IS_MERCHANT_TO_MERCHANT_USER_CONJUNCTION_BY_USERNAME_ENABLED = false;

    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ROOT_GROUP
     *
     * @var string
     */
    protected const ROOT_GROUP = 'root_group';

    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::ROLE_BACK_OFFICE_USER
     *
     * @var string
     */
    protected const AUTH_ROLE_BACK_OFFICE_USER = 'ROLE_BACK_OFFICE_USER';

    /**
     * Specification:
     * - Defines set of AclRules to assigned for merchant-specific AclRole.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclRuleExpanderPluginInterface} instead.
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
            'price-product-merchant-relationship-merchant-portal-gui',
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
     * @deprecated Use {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface} instead.
     *
     * @return array<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function getMerchantAclRoleEntityRules(): array
    {
        $aclEntityRuleTransfers = [];

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\ProductOffer\Persistence\SpyProductOffer')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Product\Persistence\SpyProductAbstract')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Merchant\Persistence\SpyMerchant')
            ->setScope(AclEntityConstants::SCOPE_SEGMENT)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Currency\Persistence\SpyCurrency')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Country\Persistence\SpyCountry')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Store\Persistence\SpyStore')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Locale\Persistence\SpyLocale')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Sales\Persistence\SpySalesOrder')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Sales\Persistence\SpySalesOrderTotals')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Sales\Persistence\SpySalesOrderAddress')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Sales\Persistence\SpySalesOrderItem')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\PriceProduct\Persistence\SpyPriceProduct')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\ProductImage\Persistence\SpyProductImageSet')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\ProductImage\Persistence\SpyProductImage')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\PriceProduct\Persistence\SpyPriceType')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CRUD);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_DELETE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\OmsProductOfferReservation\Persistence\SpyOmsProductOfferReservation')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Oms\Persistence\SpyOmsProductReservation')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Oms\Persistence\SpyOmsTransitionLog')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Url\Persistence\SpyUrlRedirect')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_DELETE | AclEntityConstants::OPERATION_MASK_UPDATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Sales\Persistence\SpySalesExpense')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Refund\Persistence\SpyRefund')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_CREATE,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Customer\Persistence\SpyCustomer')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Discount\Persistence\SpyDiscount')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Discount\Persistence\SpyDiscountAmount')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Discount\Persistence\SpyDiscountStore')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Discount\Persistence\SpyDiscountVoucher')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion')
            ->setScope(AclEntityConstants::SCOPE_INHERITED)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Sales\Persistence\SpySalesDiscount')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(
                AclEntityConstants::OPERATION_MASK_READ,
            );

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Sales\Persistence\SpySalesDiscountCode')
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
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclRuleExpanderPluginInterface} instead.
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
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclEntityRuleExpanderPluginInterface} instead.
     *
     * @return array<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function getMerchantUserAclRoleEntityRules(): array
    {
        $aclEntityRuleTransfers = [];

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\MerchantUser\Persistence\SpyMerchantUser')
            ->setScope(AclEntityConstants::SCOPE_SEGMENT)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\User\Persistence\SpyUser')
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
     * @return array<\Generated\Shared\Transfer\AclEntityRuleTransfer>
     */
    public function getProductViewerForOfferCreationAclRoleEntityRules(): array
    {
        $aclEntityRuleTransfers = [];

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Product\Persistence\SpyProductAbstract')
            ->setScope(AclEntityConstants::SCOPE_GLOBAL)
            ->setPermissionMask(AclEntityConstants::OPERATION_MASK_READ);

        $aclEntityRuleTransfers[] = (new AclEntityRuleTransfer())
            ->setEntity('Orm\Zed\Product\Persistence\SpyProduct')
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
     * @return array<\Generated\Shared\Transfer\RuleTransfer>
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

    /**
     * Specification:
     * - Defines set of Authentication Roles to provide Merchant User login to backoffice.
     *
     * @api
     *
     * @return array<string>
     */
    public function getRolesWithBackofficeAccess(): array
    {
        return [
            static::AUTH_ROLE_BACK_OFFICE_USER,
        ];
    }

    /**
     * Specification:
     * - Defines set of Acl Group references of Merchant Used to provide login to backoffice.
     *
     * @api
     *
     * @return array<string>
     */
    public function getBackofficeAllowedAclGroupReferences(): array
    {
        return [
            static::ROOT_GROUP,
        ];
    }

    /**
     * Specification:
     * - Defines whether merchant to merchant user conjunction should be generated by using users username or not.
     * - If set to true, the `UserTransfer.username` is used, otherwise `UserTransfer.firstname` and `UserTransfer.lastname`.
     *
     * @api
     *
     * @return bool
     */
    public function isMerchantToMerchantUserConjunctionByUsernameEnabled(): bool
    {
        return static::IS_MERCHANT_TO_MERCHANT_USER_CONJUNCTION_BY_USERNAME_ENABLED;
    }
}
