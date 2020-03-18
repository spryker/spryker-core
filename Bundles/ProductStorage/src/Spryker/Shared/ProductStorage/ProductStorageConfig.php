<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ProductStorageConfig extends AbstractSharedConfig
{
    /**
     * Defines queue name for publish.
     */
    public const PUBLISH_PRODUCT_ABSTRACT = 'publish.product_abstract';

    /**
     * Defines queue name for publish.
     */
    public const PUBLISH_PRODUCT_CONCRETE = 'publish.product_concrete';

    /**
     * Defines error queue name as used when with asynchronous event handling
     */
    public const PUBLISH_PRODUCT_ABSTRACT_ERROR_QUEUE = 'publish.product_abstract.error';

    /**
     * Defines retry queue name as used when with asynchronous event handling.
     */
    public const PUBLISH_PRODUCT_ABSTRACT_RETRY_QUEUE = 'publish.product_abstract.retry';

    /**
     * Defines error queue name as used when with asynchronous event handling
     */
    public const PUBLISH_PRODUCT_CONCRETE_ERROR_QUEUE = 'publish.product_concrete.error';

    /**
     * Defines retry queue name as used when with asynchronous event handling.
     */
    public const PUBLISH_PRODUCT_CONCRETE_RETRY_QUEUE = 'publish.product_concrete.retry';
}
