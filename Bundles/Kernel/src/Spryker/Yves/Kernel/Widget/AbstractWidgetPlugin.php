<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

use ArrayAccess;
use Exception;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Spryker\Yves\Kernel\Exception\InvalidWidgetPluginException;

abstract class AbstractWidgetPlugin extends AbstractPlugin implements WidgetPluginInterface, ArrayAccess
{

    /**
     * @var array
     */
    protected $widgets;

    /**
     * @var array
     */
    protected $parameters = [];

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
     * @param array $widgetClasses
     *
     * @return $this
     */
    protected function addWidgets(array $widgetClasses)
    {
        foreach ($widgetClasses as $widgetClass) {
            $this->addWidget($widgetClass);
        }

        return $this;
    }

    /**
     * @param string $widgetClassName
     *
     * @return $this
     */
    protected function addWidget(string $widgetClassName)
    {
        $this->assertClassIsWidgetPlugin($widgetClassName);

        $this->widgets[$widgetClassName::getName()] = $widgetClassName;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    protected function addParameter(string $name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->parameters[$offset]) || array_key_exists($offset, $this->parameters);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->parameters[$offset];
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
