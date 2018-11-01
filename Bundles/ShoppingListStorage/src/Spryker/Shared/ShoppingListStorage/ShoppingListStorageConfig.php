<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ShoppingListStorage;

interface ShoppingListStorageConfig
{
    public const RESOURCE_TYPE_SHOPPING_LIST = 'shopping_list_customer';

    /**
     * Specification:
     * - Queue name as used for processing shopping list messages.
     *
     * @api
     */
    public const SHOPPING_LIST_SYNC_STORAGE_QUEUE = 'sync.storage.shopping_list';

    /**
     * Specification:
     * - Queue name as used for processing shopping list error messages.
     *
     * @api
     */
    public const SHOPPING_LIST_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.shopping_list.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const SHOPPING_LIST_RESOURCE_NAME = 'shopping_list_customer';
}
