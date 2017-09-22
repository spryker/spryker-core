<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Controller;

use Spryker\Yves\Kernel\Controller\Widget;
use Symfony\Component\HttpFoundation\Request;

class View
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
     * @var \Spryker\Yves\Kernel\Controller\Widget[]
     */
    protected $widgets = [];

    /**
     * @param array $data
     * @param string|null $template
     */
    public function __construct(array $data = [], $template = null)
    {
        $this->data = $data;
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return \Spryker\Yves\Kernel\Controller\Widget[]
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasWidget($name)
    {
        return isset($this->widgets[$name]);
    }

    /**
     * @param string $name
     *
     * @return \Spryker\Yves\Kernel\Controller\Widget
     */
    public function getWidget($name)
    {
        // TODO: throw custom exception if not exists
        return $this->widgets[$name];
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Plugin\WidgetBuilderPluginInterface[] $widgetBuilderPlugins
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return $this
     */
    public function buildWidgets(array $widgetBuilderPlugins, Request $request)
    {
        // TODO: consider only registering them somehow (maybe as pimple services) and lazy load them the first time when needed for render
        foreach ($widgetBuilderPlugins as $widgetBuilderPlugin) {
            $this->addWidget($widgetBuilderPlugin->buildWidget($this, $request));
        }

        return $this;
    }

    /**
     * @param \Spryker\Yves\Kernel\Controller\Widget $widget
     *
     * @return $this
     */
    protected function addWidget(Widget $widget)
    {
        $widget->setView($this);
        $this->widgets[$widget->getName()] = $widget;

        return $this;
    }

}
