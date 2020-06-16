<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentNavigation;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentNavigationConfig extends AbstractSharedConfig
{
    /**
     * Content item navigation
     */
    public const CONTENT_TYPE_NAVIGATION = 'Navigation';

    /**
     * Content item navigation
     */
    public const CONTENT_TERM_NAVIGATION = 'Navigation';

    /**
     * Content item navigation tree-inline template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE = 'tree-inline';

    /**
     * Content item navigation tree template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_TREE = 'tree';

    /**
     * Content item navigation list-inline template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_LIST_INLINE = 'list-inline';

    /**
     * Content item navigation list template identifier
     */
    public const WIDGET_TEMPLATE_IDENTIFIER_LIST = 'list';

    /**
     * Content item navigation function name
     */
    public const TWIG_FUNCTION_NAME = 'content_navigation';
}
