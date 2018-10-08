<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Dependency;

interface ShoppingListEvents
{
    /**
     * Specification:
     * - This event will be used for spy_shopping_list entity creation
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_CREATE = 'Entity.spy_shopping_list.create';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list entity update
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_UPDATE = 'Entity.spy_shopping_list.update';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list entity delete.
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_DELETE = 'Entity.spy_shopping_list.delete';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list_item entity create.
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_ITEM_CREATE = 'Entity.spy_shopping_list_item.create';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list_item entity update.
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_ITEM_UPDATE = 'Entity.spy_shopping_list_item.update';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list_item entity delete.
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_ITEM_DELETE = 'Entity.spy_shopping_list_item.delete';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list_company_user entity create.
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_COMPANY_USER_CREATE = 'Entity.spy_shopping_list_company_user.create';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list_company_user entity update
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_COMPANY_USER_UPDATE = 'Entity.spy_shopping_list_company_user.update';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list_company_user entity delete
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_COMPANY_USER_DELETE = 'Entity.spy_shopping_list_company_user.delete';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list_company_business_unit entity creation
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_COMPANY_BUSINESS_UNIT_CREATE = 'Entity.spy_shopping_list_company_business_unit.create';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list_company_business_unit entity update
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_COMPANY_BUSINESS_UNIT_UPDATE = 'Entity.spy_shopping_list_company_business_unit.update';

    /**
     * Specification:
     * - This event will be used for spy_shopping_list_company_business_unit entity delete
     *
     * @api
     */
    public const ENTITY_SPY_SHOPPING_LIST_COMPANY_BUSINESS_UNIT_DELETE = 'Entity.spy_shopping_list_company_business_unit.delete';

    /**
     * Specification:
     * - This event is used for shopping_list unpublishing.
     *
     * @api
     */
    public const SHOPPING_LIST_UNPUBLISH = 'ShoppingList.shopping_list.unpublish';
}
