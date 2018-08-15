<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\ModuleFileFinder\PathBuilder;

use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

abstract class AbstractPathBuilder implements PathBuilderInterface
{
    /**
     * @param string $module
     *
     * @return string
     */
    protected function dasherizeModuleName(string $module): string
    {
        if ($module === '*') {
            return $module;
        }

        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($module);
    }
}
