<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentProductSetGui;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentProductSetGuiConfig extends AbstractSharedConfig
{
    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::CONTENT_TYPE_PRODUCT_SET
     *
     * Content item product set
     */
    public const CONTENT_TYPE_PRODUCT_SET = 'Product Set';

    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::CONTENT_TERM_PRODUCT_SET
     *
     * Content item product set
     */
    public const CONTENT_TERM_PRODUCT_SET = 'Product Set';
}
