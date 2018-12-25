<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ShoppingListsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_SHOPPING_LISTS = 'shopping-lists';
    public const RESOURCE_SHOPPING_LIST_ITEMS = 'shopping-list-items';

    public const CONTROLLER_SHOPPING_LIST_ITEMS = 'shopping-list-items-resource';

    public const ACTION_SHOPPING_LIST_ITEMS_POST = 'post';

    public const RESPONSE_CODE_SHOPPING_LIST_ID_NOT_SPECIFIED = '1501';

    public const RESPONSE_DETAIL_SHOPPING_LIST_ID_NOT_SPECIFIED = 'Shopping list id not specified.';

    public const FORMAT_SELF_LINK_SHOPPING_LIST_ITEMS_RESOURCE = '%s/%s/%s/%s';

    /**
     * @see \Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig::X_COMPANY_USER_ID_HEADER_KEY
     */
    public const X_COMPANY_USER_ID_HEADER_KEY = 'X-Company-User-Id';
}
