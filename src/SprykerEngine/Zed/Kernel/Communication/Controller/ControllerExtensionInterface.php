<?php

namespace SprykerEngine\Zed\Kernel\Communication\Controller;

interface ControllerExtensionInterface
{

    /**
     * @param $controller
     * @return mixed
     */
    public function extend($controller);

}
