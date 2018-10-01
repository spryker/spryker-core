<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CategoryPageSearch;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class CategoryPageSearchConstants
{
    /**
     * Specification:
     * - Queue name as used for processing category messages
     *
     * @api
     */
    public const CATEGORY_SYNC_SEARCH_QUEUE = 'sync.search.category';

    /**
     * Specification:
     * - Queue name as used for error category messages
     *
     * @api
     */
    public const CATEGORY_SYNC_SEARCH_ERROR_QUEUE = 'sync.search.category.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const CATEGORY_NODE_RESOURCE_NAME = 'category_node';
}
