<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentNavigationGui;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentNavigationGuiConfig extends AbstractSharedConfig
{
    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::CONTENT_TYPE_NAVIGATION
     *
     * Content item navigation
     */
    public const CONTENT_TYPE_NAVIGATION = 'Navigation';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::CONTENT_TERM_NAVIGATION
     *
     * Content item navigation
     */
    public const CONTENT_TERM_NAVIGATION = 'Navigation';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE
     *
     * Content item navigation tree-inline template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE = 'tree-inline';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_TREE
     *
     * Content item navigation tree template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_TREE = 'tree';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_LIST_INLINE
     *
     * Content item navigation list-inline template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_LIST_INLINE = 'list-inline';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_LIST
     *
     * Content item navigation list template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_LIST = 'list';

    /**
     * Content item navigation tree-inline template name
     */
    public const WIDGET_TEMPLATE_DISPLAY_NAME_TREE_INLINE = 'Inline Tree';

    /**
     * Content item navigation tree template name
     */
    public const WIDGET_TEMPLATE_DISPLAY_NAME_TREE = 'Tree';

    /**
     * Content item navigation list-inline template name
     */
    public const WIDGET_TEMPLATE_DISPLAY_NAME_LIST_INLINE = 'Inline List';

    /**
     * Content item navigation list template name
     */
    public const WIDGET_TEMPLATE_DISPLAY_NAME_LIST = 'List';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::TWIG_FUNCTION_NAME
     *
     * Content item navigation function name
     */
    public const TWIG_FUNCTION_NAME = 'content_navigation';

    /**
     * @api
     *
     * @return string[]
     */
    public function getContentWidgetTemplates(): array
    {
        return [
            static::WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE => static::WIDGET_TEMPLATE_DISPLAY_NAME_TREE_INLINE,
            static::WIDGET_TEMPLATE_IDENTIFIER_TREE => static::WIDGET_TEMPLATE_DISPLAY_NAME_TREE,
            static::WIDGET_TEMPLATE_IDENTIFIER_LIST_INLINE => static::WIDGET_TEMPLATE_DISPLAY_NAME_LIST_INLINE,
            static::WIDGET_TEMPLATE_IDENTIFIER_LIST => static::WIDGET_TEMPLATE_DISPLAY_NAME_LIST,
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTwigFunctionName(): string
    {
        return static::TWIG_FUNCTION_NAME;
    }
}
