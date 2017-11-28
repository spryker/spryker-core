<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

class WidgetCollection implements WidgetContainerInterface
{

    /**
     * @var \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface[]
     */
    protected $widgets = [];

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface[] $widgetPlugins
     */
    public function __construct(array $widgetPlugins = [])
    {
        $this->addWidgets($widgetPlugins);
    }
    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasWidget(string $name): bool
    {
        return isset($this->widgets[$name]);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getWidgetClassName(string $name): string
    {
        // TODO: throw custom exception if not exists
        return $this->widgets[$name];
    }

    /**
     * @param string[] $widgetBuilderPlugins
     *
     * @return void
     */
    protected function addWidgets(array $widgetBuilderPlugins): void
    {
        foreach ($widgetBuilderPlugins as $widgetClass) {
            $this->addWidget($widgetClass);
        }
    }

    /**
     * @param string $widgetClass
     *
     * @return void
     */
    protected function addWidget(string $widgetClass): void
    {
        // TODO: make sure $widgetClass implements WidgetPluginInterface
        $this->widgets[$widgetClass::getName()] = $widgetClass;
    }

}
