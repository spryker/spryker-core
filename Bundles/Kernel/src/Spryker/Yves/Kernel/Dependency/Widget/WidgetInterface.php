<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Dependency\Widget;

use ArrayAccess;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;

interface WidgetInterface extends WidgetContainerInterface, ArrayAccess
{
    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string;

    /**
     * Specification:
     * - Returns the data of the widget.
     *
     * @api
     *
     * @return array
     */
    public function getParameters(): array;
}
