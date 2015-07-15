<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

class ControllerBundleNameExtractor
{

    const BUNDLE_NAME_POSITION = 2;

    /**
     * @param string|object $controller
     *
     * @return string
     */
    public function getBundleName($controller)
    {
        if (is_object($controller)) {
            $controller = get_class($controller);
        }
        $controllerNameParts = explode('\\', $controller);

        return $controllerNameParts[self::BUNDLE_NAME_POSITION];
    }

}
