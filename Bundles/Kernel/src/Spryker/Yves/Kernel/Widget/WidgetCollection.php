<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Widget;

use Spryker\Yves\Kernel\Exception\MissingWidgetException;

class WidgetCollection implements WidgetContainerInterface
{
    use WidgetContainerAwareTrait;

    /**
     * @param string[] $widgetClassNames
     */
    public function __construct(array $widgetClassNames = [])
    {
        $this->addWidgets($widgetClassNames);
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
     * @throws \Spryker\Yves\Kernel\Exception\MissingWidgetException
     *
     * @return string
     */
    public function getWidgetClassName(string $name)
    {
        if (!isset($this->widgets[$name])) {
            throw new MissingWidgetException(sprintf(
                'Missing "%s" widget. You need to register the widgets in the constructor of the WidgetCollection in order to use them.',
                $name
            ));
        }

        return $this->widgets[$name];
    }
}
