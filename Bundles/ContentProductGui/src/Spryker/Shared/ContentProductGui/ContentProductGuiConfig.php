<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentProductGui;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentProductGuiConfig extends AbstractSharedConfig
{
    /**
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST
     *
     * Content item abstract product list
     */
    public const CONTENT_TYPE_PRODUCT_ABSTRACT_LIST = 'Abstract Product List';

    /**
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST
     *
     * Content item abstract product list
     */
    public const CONTENT_TERM_PRODUCT_ABSTRACT_LIST = 'Abstract Product List';
}
