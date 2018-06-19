<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\View;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Spryker\Yves\Kernel\Exception\InvalidWidgetPluginException;
use Spryker\Yves\Kernel\Exception\MissingWidgetPluginException;
use Spryker\Yves\Kernel\Exception\ReadOnlyException;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;

class View implements ViewInterface, WidgetContainerInterface
{
    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string[]
     */
    protected $widgets = [];

    /**
     * @param array $data
     * @param string[] $widgetPlugins
     * @param string|null $template
     */
    public function __construct(array $data = [], array $widgetPlugins = [], ?string $template = null)
    {
        $this->data = $data;
        $this->template = $template;
        $this->addWidgets($widgetPlugins);
    }

    /**
     * @return string|null
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasWidget(string $name)
    {
        return isset($this->widgets[$name]);
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Yves\Kernel\Exception\MissingWidgetPluginException
     *
     * @return string
     */
    public function getWidgetClassName(string $name)
    {
        if (!isset($this->widgets[$name])) {
            throw new MissingWidgetPluginException(sprintf(
                'Missing "%s" widget plugin. You need to register the widgets in the constructor of the View in order to use them.',
                $name
            ));
        }

        return $this->widgets[$name];
    }

    /**
     * @param string[] $widgetClassNames
     *
     * @return void
     */
    protected function addWidgets(array $widgetClassNames)
    {
        foreach ($widgetClassNames as $widgetClassName) {
            $this->addWidget($widgetClassName);
        }
    }

    /**
     * @param string $widgetClassName
     *
     * @return void
     */
    protected function addWidget(string $widgetClassName)
    {
        $this->assertClassIsWidgetPlugin($widgetClassName);

        $this->widgets[$widgetClassName::getName()] = $widgetClassName;
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]) || array_key_exists($offset, $this->data);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws \Spryker\Yves\Kernel\Exception\ReadOnlyException
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new ReadOnlyException('This is a read only object.');
    }

    /**
     * @param mixed $offset
     *
     * @throws \Spryker\Yves\Kernel\Exception\ReadOnlyException
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new ReadOnlyException('This is a read only object.');
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
}
