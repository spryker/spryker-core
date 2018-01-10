<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

interface WidgetContainerInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasWidget(string $name);

    /**
     * @param string $name
     *
     * @return string
     */
    public function getWidgetClassName(string $name);
}
