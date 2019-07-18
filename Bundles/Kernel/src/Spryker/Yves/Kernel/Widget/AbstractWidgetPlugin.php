<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

use ArrayAccess;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Spryker\Yves\Kernel\Exception\MissingWidgetPluginException;
use Spryker\Yves\Kernel\Exception\ReadOnlyException;

/**
 * @deprecated Use \Spryker\Yves\Kernel\Widget\AbstractWidget instead.
 */
abstract class AbstractWidgetPlugin extends AbstractPlugin implements WidgetPluginInterface, ArrayAccess
{
    use WidgetContainerAwareTrait;

    /**
     * @var array
     */
    protected $parameters = [];

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
                'Missing "%s" widget plugin. You need to register your sub-widgets in order to use them. You can use $this->addWidgets() and $this->addWidget() methods.',
                $name
            ));
        }

        return $this->widgets[$name];
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
}
