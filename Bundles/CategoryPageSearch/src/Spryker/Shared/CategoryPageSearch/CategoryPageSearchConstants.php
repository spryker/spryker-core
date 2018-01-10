<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CategoryPageSearch;

class CategoryPageSearchConstants
{
    /**
     * Specification:
     * - Queue name as used for processing cms block messages
     *
     * @api
     */
    const CATEGORY_SYNC_SEARCH_QUEUE = 'sync.search.category';

    /**
     * Specification:
     * - Queue name as used for error cms block messages
     *
     * @api
     */
    const CATEGORY_SYNC_SEARCH_ERROR_QUEUE = 'sync.search.category.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const CATEGORY_NODE_RESOURCE_NAME = 'category_node';
}
