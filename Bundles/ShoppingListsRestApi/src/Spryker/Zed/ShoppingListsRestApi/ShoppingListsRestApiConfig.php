<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi;

use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ShoppingListsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Zed\ShoppingList\Business\Model\ShoppingListWriter::DUPLICATE_NAME_SHOPPING_LIST
     *
     * @var string
     */
    public const DUPLICATE_NAME_SHOPPING_LIST = 'customer.account.shopping_list.error.duplicate_name';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\Model\ShoppingListWriter::CANNOT_UPDATE_SHOPPING_LIST
     *
     * @var string
     */
    public const CANNOT_UPDATE_SHOPPING_LIST = 'customer.account.shopping_list.error.cannot_update';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\Model\ShoppingListWriter::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED
     *
     * @var string
     */
    public const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED = 'customer.account.shopping_list.delete.failed';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidator::ERROR_SHOPPING_LIST_NOT_FOUND
     *
     * @var string
     */
    public const ERROR_SHOPPING_LIST_NOT_FOUND = 'customer.account.shopping_list.error.not_found';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemPermissionValidator::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED
     *
     * @var string
     */
    public const ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = 'customer.account.shopping_list.error.write_permission_required';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidator::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID
     *
     * @var string
     */
    public const ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID = 'customer.account.shopping_list_item.error.quantity_not_valid';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidator::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND
     *
     * @var string
     */
    public const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND = 'customer.account.shopping_list_item.error.product_not_found';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\Model\ShoppingListItemOperation::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE
     *
     * @var string
     */
    protected const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE = 'customer.account.shopping_list_item.error.product_not_active';

    /**
     * @see \Spryker\Zed\ProductApproval\Business\Validator\ProductApprovalShoppingListValidator::GLOSSARY_KEY_PRODUCT_NOT_APPROVED
     *
     * @var string
     */
    protected const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_APPROVED = 'product-approval.message.not-approved';

    /**
     * @see \Spryker\Zed\ProductDiscontinued\Business\ShoppingListCheck\ShoppingListAddItemPreCheck::SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED
     *
     * @var string
     */
    public const SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED = 'shopping_list.pre.check.product_discontinued';

    /**
     * @see \Spryker\Zed\MerchantProduct\Business\Checker\MerchantProductShoppingListItemChecker::GLOSSARY_KEY_PRODUCT_MERCHANT_INACTIVE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_MERCHANT_INACTIVE = 'shopping_list.pre.check.product_merchant_inactive';

    /**
     * @see \Spryker\Zed\MerchantProduct\Business\Checker\MerchantProductShoppingListItemChecker::GLOSSARY_KEY_PRODUCT_MERCHANT_NOT_APPROVED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_MERCHANT_NOT_APPROVED = 'shopping_list.pre.check.product_merchant_not_approved';

    /**
     * @see \Spryker\Zed\MerchantProductOffer\Business\Checker\MerchantProductOfferChecker::GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_INACTIVE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_INACTIVE = 'shopping_list.pre.check.product_merchant_inactive';

    /**
     * @see \Spryker\Zed\MerchantProductOffer\Business\Checker\MerchantProductOfferChecker::GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_NOT_APPROVED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_NOT_APPROVED = 'shopping_list.pre.check.product_merchant_not_approved';

    /**
     * @see \Spryker\Zed\ProductOfferShoppingList\Business\Checker\ProductOfferShoppingListChecker::ERROR_SHOPPING_LIST_PRE_CHECK_PRODUCT_OFFER_APPROVED
     *
     * @var string
     */
    protected const SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_NOT_APPROVED = 'shopping_list.pre.check.product_offer.approved';

    /**
     * @see \Spryker\Zed\ProductOfferShoppingList\Business\Checker\ProductOfferShoppingListChecker::ERROR_SHOPPING_LIST_PRE_CHECK_PRODUCT_OFFER_IS_ACTIVE
     *
     * @var string
     */
    protected const SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_NOT_ACTIVE = 'shopping_list.pre.check.product_offer.is_active';

    /**
     * @see \Spryker\Zed\ProductOfferShoppingList\Business\Checker\ProductOfferShoppingListChecker::ERROR_SHOPPING_LIST_PRE_CHECK_PRODUCT_OFFER
     *
     * @var string
     */
    protected const SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_NOT_FOUND = 'shopping_list.pre.check.product_offer';

        /**
         * @see \Spryker\Zed\ProductOfferShoppingList\Business\Checker\ProductOfferShoppingListChecker::GLOSSARY_KEY_PRODUCT_OFFER_STORE_INVALID
         *
         * @var string
         */
    protected const GLOSSARY_KEY_PRODUCT_OFFER_STORE_INVALID = 'shopping_list.pre.check.product_offer.store_invalid';

    /**
     * @see \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemChecker::GLOSSARY_KEY_PRODUCT_STORE_INVALID
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_STORE_INVALID = 'shopping_list.pre.check.product.store_invalid';

    /**
     * @api
     *
     * @return array<string, string>
     */
    public static function getResponseErrorMapping(): array
    {
        return [
            static::DUPLICATE_NAME_SHOPPING_LIST => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_DUPLICATE_NAME,
            static::CANNOT_UPDATE_SHOPPING_LIST => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
            static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
            static::ERROR_SHOPPING_LIST_NOT_FOUND => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_NOT_FOUND,
            static::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
            static::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_WRONG_QUANTITY,
            static::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRODUCT_NOT_FOUND,
            static::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_PRODUCT_NOT_ACTIVE,
            static::SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_DISCONTINUED,
            static::GLOSSARY_KEY_PRODUCT_OFFER_STORE_INVALID => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_STORE_INVALID,
            static::GLOSSARY_KEY_PRODUCT_STORE_INVALID => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_STORE_INVALID,
            static::GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_INACTIVE => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_MERCHANT_INACTIVE,
            static::GLOSSARY_KEY_PRODUCT_OFFER_MERCHANT_NOT_APPROVED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_MERCHANT_NOT_APPROVED,
            static::GLOSSARY_KEY_PRODUCT_MERCHANT_INACTIVE => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_MERCHANT_INACTIVE,
            static::GLOSSARY_KEY_PRODUCT_MERCHANT_NOT_APPROVED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_MERCHANT_NOT_APPROVED,
            static::SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_NOT_APPROVED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_NOT_APPROVED,
            static::SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_NOT_ACTIVE => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_NOT_ACTIVE,
            static::SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_NOT_FOUND => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_OFFER_NOT_FOUND,
            static::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_APPROVED => SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_PRE_ADD_CHECK_PRODUCT_NOT_APPROVED,
        ];
    }
}
