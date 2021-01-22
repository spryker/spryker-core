<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router\Helper;

trait RouterHelperTrait
{
    /**
     * @return \SprykerTest\Zed\Router\Helper\RouterHelper
     */
    protected function getRouterHelper(): RouterHelper
    {
        /** @var \SprykerTest\Zed\Router\Helper\RouterHelper $routerHelper */
        $routerHelper = $this->getModule('\\' . RouterHelper::class);

        return $routerHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
