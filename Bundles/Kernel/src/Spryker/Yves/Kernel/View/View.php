<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\View;

use Exception;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;

class View implements ViewInterface, WidgetContainerInterface
{

    /**
     * @var string
     */
    protected $template;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected $data;

    /**
     * @var \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface[]
     */
    protected $widgets = [];

    /**
     * @param array $data
     * @param \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface[] $widgetPlugins
     * @param string|null $template
     */
    public function __construct(array $data = [], array $widgetPlugins = [], string $template = null)
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
     * @throws \Exception
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        // TODO: customize exception
        throw new Exception('This is a ready only object.');
    }

    /**
     * @param mixed $offset
     *
     * @throws \Exception
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        // TODO: customize exception
        throw new Exception('This is a ready only object.');
    }

}
