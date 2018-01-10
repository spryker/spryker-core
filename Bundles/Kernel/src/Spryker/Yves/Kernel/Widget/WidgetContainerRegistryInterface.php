<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

interface WidgetContainerRegistryInterface
{
    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetContainerInterface $widgetContainer
     *
     * @return void
     */
    public function add(WidgetContainerInterface $widgetContainer);

    /**
     * @return void
     */
    public function removeLastAdded();

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface|null
     */
    public function getLastAdded();
}
