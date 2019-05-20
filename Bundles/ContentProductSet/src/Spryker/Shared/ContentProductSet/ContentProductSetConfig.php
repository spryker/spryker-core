<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentProductSet;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentProductSetConfig extends AbstractSharedConfig
{
    /**
     * Content item product set
     */
    public const CONTENT_TYPE_PRODUCT_SET = 'Product Set';

    /**
     * Content item product set
     */
    public const CONTENT_TERM_PRODUCT_SET = 'Product Set';

    /**
     * Content item product set twig function name
     */
    public const TWIG_FUNCTION_NAME = 'content_product_set';

    /**
     * Content item product set default template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_DEFAULT = 'default';

    /**
     * Content item product set default template name
     */
    public const WIDGET_TEMPLATE_DISPLAY_NAME_DEFAULT = 'content_product_set.template.default';

    /**
     * Content item product set cart-button-btm template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_CART_BUTTON_BOTTOM = 'cart-button-btm';

    /**
     * Content item product set cart-button-btm template name
     */
    public const WIDGET_TEMPLATE_DISPLAY_NAME_TOP_TITLE = 'content_product_set.template.cart-button-btm';
}
