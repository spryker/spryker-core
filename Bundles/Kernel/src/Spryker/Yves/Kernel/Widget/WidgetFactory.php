<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Spryker\Yves\Kernel\Exception\InvalidWidgetPluginException;

/**
 * @deprecated Use \SprykerShop\Yves\ShopApplication\Twig\Widget\WidgetFactory instead.
 */
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
     * @return \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface
     */
    public function build(string $widgetClassName, array $arguments = [])
    {
        $cacheKey = $this->generateCacheKey($widgetClassName, $arguments);
        $widget = $this->getCachedWidget($cacheKey);
        if ($widget) {
            return $widget;
        }

        $this->assertClassIsWidgetPlugin($widgetClassName);
        $this->assertInitializeExists($widgetClassName);

        $widget = new $widgetClassName();
        call_user_func_array([$widget, 'initialize'], $arguments);

        $this->cacheWidget($cacheKey, $widget);

        return $widget;
    }

    /**
     * @param string $widgetClassName
     *
     * @throws \Spryker\Yves\Kernel\Exception\InvalidWidgetPluginException
     *
     * @return void
     */
    protected function assertClassIsWidgetPlugin(string $widgetClassName)
    {
        if (!is_subclass_of($widgetClassName, WidgetPluginInterface::class)) {
            throw new InvalidWidgetPluginException(sprintf(
                'Invalid widget plugin %s. This class needs to implement %s.',
                $widgetClassName,
                WidgetPluginInterface::class
            ));
        }
    }

    /**
     * @param string $widgetClassName
     *
     * @throws \Spryker\Yves\Kernel\Exception\InvalidWidgetPluginException
     *
     * @return void
     */
    protected function assertInitializeExists(string $widgetClassName)
    {
        if (!method_exists($widgetClassName, 'initialize')) {
            throw new InvalidWidgetPluginException(sprintf(
                'Widget %s needs to define and implement custom initialize() method with its custom widget input parameters.',
                $widgetClassName
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
     * @return \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|null
     */
    protected function getCachedWidget(string $cacheKey)
    {
        return static::$widgetCache[$cacheKey] ?? null;
    }

    /**
     * @param string $cacheKey
     * @param \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface $widget
     *
     * @return void
     */
    protected function cacheWidget(string $cacheKey, WidgetPluginInterface $widget)
    {
        static::$widgetCache[$cacheKey] = $widget;
    }
}
