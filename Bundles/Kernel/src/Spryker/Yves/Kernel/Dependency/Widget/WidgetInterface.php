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
     * @return string
     */
    public static function getName(): string;

    /**
     * @return string
     */
    public static function getTemplate(): string;

    /**
     * @return array
     */
    public function getParameters(): array;
}
