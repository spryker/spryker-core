<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
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
    public function hasWidget(string $name): bool;

    /**
     * @param string $name
     *
     * @return string
     */
    public function getWidgetClassName(string $name): string;

}
