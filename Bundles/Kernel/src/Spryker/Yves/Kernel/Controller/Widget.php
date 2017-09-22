<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Controller;

class Widget
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Spryker\Yves\Kernel\Controller\View
     */
    protected $view;

    /**
     * @param string $name
     * @param string $template
     * @param array $data
     */
    public function __construct($name, $template, array $data = [])
    {
        $this->name = $name;
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return void
     */
    public function setTemplate($template)
    {
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
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Spryker\Yves\Kernel\Controller\View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param \Spryker\Yves\Kernel\Controller\View $view
     *
     * @return void
     */
    public function setView($view)
    {
        $this->view = $view;
    }

}
