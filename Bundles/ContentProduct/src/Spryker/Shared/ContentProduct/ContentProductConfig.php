<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentProduct;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentProductConfig extends AbstractSharedConfig
{
    /**
     * Content item abstract product list
     */
    public const CONTENT_TYPE_PRODUCT_ABSTRACT_LIST = 'Abstract Product List';

    /**
     * Content item abstract product list
     */
    public const CONTENT_TERM_PRODUCT_ABSTRACT_LIST = 'Abstract Product List';

    /**
     * Content item abstract product list function name
     */
    public const TWIG_FUNCTION_NAME = 'content_product_abstract_list';

    /**
     * Content item abstract product list bottom-title template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE = 'bottom-title';

    /**
     * Content item abstract product list top-title template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';
}
