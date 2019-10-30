<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

class WidgetContainerRegistry implements WidgetContainerRegistryInterface
{
    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetContainerInterface[]
     */
    protected static $widgetContainerStack = [];

    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetContainerInterface $widgetContainer
     *
     * @return void
     */
    public function add(WidgetContainerInterface $widgetContainer)
    {
        static::$widgetContainerStack[] = $widgetContainer;
    }

    /**
     * @return void
     */
    public function removeLastAdded()
    {
        array_pop(static::$widgetContainerStack);
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface|null
     */
    public function getLastAdded()
    {
        return static::$widgetContainerStack ? end(static::$widgetContainerStack) : null;
    }
}
