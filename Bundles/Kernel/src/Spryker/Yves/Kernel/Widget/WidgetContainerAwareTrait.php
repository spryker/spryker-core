<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;
use Spryker\Yves\Kernel\Exception\InvalidWidgetException;
use Spryker\Yves\Kernel\Exception\UniqueWidgetNameException;

trait WidgetContainerAwareTrait
{
    /**
     * @var string[]
     */
    protected $widgets = [];

    /**
     * @param string[] $widgetClassNames
     *
     * @return $this
     */
    protected function addWidgets(array $widgetClassNames): self
    {
        foreach ($widgetClassNames as $widgetClassName) {
            $this->addWidget($widgetClassName);
        }

        return $this;
    }

    /**
     * @param string $widgetClassName
     *
     * @return $this
     */
    protected function addWidget(string $widgetClassName): self
    {
        $this->assertClassIsWidget($widgetClassName);

        $widgetName = $this->getUniqueWidgetName($widgetClassName);

        $this->widgets[$widgetName] = $widgetClassName;

        return $this;
    }

    /**
     * @param string $widgetClassName
     *
     * @throws \Spryker\Yves\Kernel\Exception\InvalidWidgetException
     *
     * @return void
     */
    protected function assertClassIsWidget(string $widgetClassName): void
    {
        if (is_subclass_of($widgetClassName, WidgetInterface::class)) {
            return;
        }

        if (is_subclass_of($widgetClassName, WidgetPluginInterface::class)) {
            return;
        }

        throw new InvalidWidgetException(sprintf(
            'Invalid widget %s. This class needs to implement %s (or the deprecated %s).',
            $widgetClassName,
            WidgetInterface::class,
            WidgetPluginInterface::class
        ));
    }

    /**
     * @param string $widgetClassName
     *
     * @throws \Spryker\Yves\Kernel\Exception\UniqueWidgetNameException
     *
     * @return string
     */
    protected function getUniqueWidgetName(string $widgetClassName): string
    {
        $widgetName = $widgetClassName::getName();

        if (isset($this->widgets[$widgetName])) {
            throw new UniqueWidgetNameException(sprintf(
                'Name "%s" of widget %s must be unique in its scope of registered widgets.',
                $widgetName,
                $widgetClassName
            ));
        }

        return $widgetName;
    }
}
