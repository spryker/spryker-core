<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\PathBuilder;

use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class SprykerStandAloneModulePathBuilder extends AbstractPathBuilder
{
    /**
     * @var string
     */
    protected $basePath;

    public function __construct()
    {
        $this->basePath = APPLICATION_VENDOR_DIR . '/spryker/';
    }

    /**
     * @param string $module
     *
     * @return array
     */
    public function buildPaths(string $module): array
    {
        $paths = [
            sprintf('%s%s/', $this->basePath, $this->dasherize($module)),
        ];

        return $paths;
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function dasherize(string $module): string
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($module);
    }
}
