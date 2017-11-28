<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

use Silex\Application;

class WidgetContainerRegistry implements WidgetContainerRegistryInterface
{

    protected const WIDGET_CONTAINER_STACK = 'widget_container_stack';

    /**
     * @var \Silex\Application
     */
    protected $application;

    /**
     * @param \Silex\Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->initializeWidgetContainerService();
    }

    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetContainerInterface $widgetContainer
     *
     * @return void
     */
    public function add(WidgetContainerInterface $widgetContainer)
    {
        $this->application[static::WIDGET_CONTAINER_STACK] = $this->application->extend(static::WIDGET_CONTAINER_STACK, function ($stack) use ($widgetContainer) {
            $stack[] = $widgetContainer;

            return $stack;
        });
    }

    /**
     * @return void
     */
    public function removeLastAdded()
    {
        $this->application[static::WIDGET_CONTAINER_STACK] = $this->application->extend(static::WIDGET_CONTAINER_STACK, function ($stack) {
            array_pop($stack);

            return $stack;
        });
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface|null
     */
    public function getLastAdded()
    {
        $stack = $this->application[static::WIDGET_CONTAINER_STACK];

        return $stack ? end($stack) : null;
    }

    /**
     * @return void
     */
    protected function initializeWidgetContainerService()
    {
        if (isset($this->application[static::WIDGET_CONTAINER_STACK])) {
            return;
        }

        $this->application[static::WIDGET_CONTAINER_STACK] = $this->application->share(function () {
            return [];
        });
    }

}
