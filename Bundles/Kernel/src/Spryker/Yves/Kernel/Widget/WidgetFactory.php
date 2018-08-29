<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;
use Spryker\Yves\Kernel\Exception\InvalidWidgetException;

class WidgetFactory implements WidgetFactoryInterface
{
    /**
     * @var array
     */
    protected static $widgetCache = [];

    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface
     */
    public function build(string $widgetClassName, array $arguments): WidgetInterface
    {
        $cacheKey = $this->generateCacheKey($widgetClassName, $arguments);
        $widget = $this->getCachedWidget($cacheKey);
        if ($widget) {
            return $widget;
        }

        $this->assertClassIsWidget($widgetClassName);

        $widget = new $widgetClassName(...$arguments);

        $this->cacheWidget($cacheKey, $widget);

        return $widget;
    }

    /**
     * @param string $widgetClassName
     *
     * @throws \Spryker\Yves\Kernel\Exception\InvalidWidgetException
     *
     * @return void
     */
    protected function assertClassIsWidget(string $widgetClassName)
    {
        if (!is_subclass_of($widgetClassName, WidgetInterface::class)) {
            throw new InvalidWidgetException(sprintf(
                'Invalid widget %s. This class needs to implement %s.',
                $widgetClassName,
                WidgetInterface::class
            ));
        }
    }

    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return string
     */
    protected function generateCacheKey(string $widgetClassName, array $arguments)
    {
        return md5($widgetClassName . serialize($arguments));
    }

    /**
     * @param string $cacheKey
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|null
     */
    protected function getCachedWidget(string $cacheKey): ?WidgetInterface
    {
        return static::$widgetCache[$cacheKey] ?? null;
    }

    /**
     * @param string $cacheKey
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface $widget
     *
     * @return void
     */
    protected function cacheWidget(string $cacheKey, WidgetInterface $widget)
    {
        static::$widgetCache[$cacheKey] = $widget;
    }
}
