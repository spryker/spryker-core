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
     * Content item abstract product list default template identifier
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_DEFAULT = 'default';

    /**
     * Content item abstract product list default template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_DEFAULT = 'content_product_abstract_list.template.default';

    /**
     * Content item abstract product list top-title template identifier
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';

    /**
     * Content item abstract product list top-title template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_TOP_TITLE = 'content_product_abstract_list.template.top-title';
}
