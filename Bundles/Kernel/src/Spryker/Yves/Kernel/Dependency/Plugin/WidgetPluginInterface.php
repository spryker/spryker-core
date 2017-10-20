<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Dependency\Plugin;

use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;

/**
 * TODO: write where/how this class used
 */
interface WidgetPluginInterface extends WidgetContainerInterface
{

    /**
     * TODO: add specification
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * TODO: add specification
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string;

}
