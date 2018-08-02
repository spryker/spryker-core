<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ShoppingListStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class ShoppingListStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing category messages
     *
     * @api
     */
    const SHOPPING_LIST_SYNC_STORAGE_QUEUE = 'sync.storage.shopping_list';
}
