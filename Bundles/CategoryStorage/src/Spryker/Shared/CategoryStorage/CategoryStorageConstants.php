<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CategoryStorage;

class CategoryStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing cms block messages
     *
     * @api
     */
    const CATEGORY_SYNC_STORAGE_QUEUE = 'sync.storage.category';

    /**
     * Specification:
     * - Queue name as used for error cms block messages
     *
     * @api
     */
    const CATEGORY_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.category.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const CATEGORY_NODE_RESOURCE_NAME = 'category_node';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const CATEGORY_TREE_RESOURCE_NAME = 'category_tree';
}
