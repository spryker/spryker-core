<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Dependency\Plugin;

use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;

/**
 * @deprecated Use \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface instead.
 */
interface WidgetPluginInterface extends WidgetContainerInterface
{
    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName();

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate();
}
