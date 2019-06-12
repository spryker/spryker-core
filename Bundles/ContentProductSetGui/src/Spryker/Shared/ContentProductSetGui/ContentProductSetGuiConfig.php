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

    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::TWIG_FUNCTION_NAME
     */
    protected const TWIG_FUNCTION_NAME = 'content_product_set';

    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::WIDGET_TEMPLATE_IDENTIFIER_DEFAULT
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_DEFAULT = 'default';

    /**
     * Content item product set default template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_DEFAULT = 'content_product_set_gui.template.default';

    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::WIDGET_TEMPLATE_IDENTIFIER_CART_BUTTON_BOTTOM
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_CART_BUTTON_BOTTOM = 'cart-button-btm';

    /**
     * Content item product set cart-button-btm template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_CART_BUTTON_BOTTOM = 'content_product_set_gui.template.cart-button-btm';

    /**
     * @return string[]
     */
    public function getContentWidgetTemplates(): array
    {
        return [
            static::WIDGET_TEMPLATE_IDENTIFIER_DEFAULT => static::WIDGET_TEMPLATE_DISPLAY_NAME_DEFAULT,
            static::WIDGET_TEMPLATE_IDENTIFIER_CART_BUTTON_BOTTOM => static::WIDGET_TEMPLATE_DISPLAY_NAME_CART_BUTTON_BOTTOM,
        ];
    }

    /**
     * @return string
     */
    public function getTwigFunctionName(): string
    {
        return static::TWIG_FUNCTION_NAME;
    }
}
