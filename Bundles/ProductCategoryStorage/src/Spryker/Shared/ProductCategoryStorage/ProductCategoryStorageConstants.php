<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductCategoryStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ProductCategoryStorageConstants
{
    /**
     * Specification:
     * - Defines the number of product abstract ids in the batch to be written.
     *
     * @api
     *
     * @var string
     */
    public const WRITE_COLLECTION_BATCH_SIZE = 'PRODUCT_CATEGORY_STORAGE:WRITE_COLLECTION_BATCH_SIZE';
}
