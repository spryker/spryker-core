<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication\Controller;

/**
 * @codeCoverageIgnore
 *
 * @deprecated this class will be removed
 */
class ControllerExtender
{

    /**
     * @var ControllerExtensionInterface[]
     */
    protected $controllerExtensions = [];

    /**
     * @param $controller
     *
     * @return mixed
     */
    protected function applyExtensions($controller)
    {
        foreach ($this->controllerExtensions as $controllerExtension) {
            $controllerExtension->extend($controller);
        }

        return $controller;
    }

    /**
     * @param array $extensions
     *
     * @return $this
     */
    public function addControllerExtensions(array $extensions)
    {
        foreach ($extensions as $extension) {
            $this->addControllerExtension($extension);
        }

        return $this;
    }

    /**
     * @param ControllerExtensionInterface $extension
     *
     * @return $this
     */
    public function addControllerExtension(ControllerExtensionInterface $extension)
    {
        $this->controllerExtensions[] = $extension;

        return $this;
    }

}
