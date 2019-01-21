<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;
use Zend\Filter\Word\UnderscoreToCamelCase;

class DescriptionUpdater implements UpdaterInterface
{
    public const KEY_DESCRIPTION = 'description';

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $composerJson[static::KEY_DESCRIPTION] = $this->getModuleNameFromFullPath($composerJsonFile->getPath()) . ' module';

        return $composerJson;
    }

    /**
     * @param string $fullPath
     *
     * @return string
     */
    protected function getModuleNameFromFullPath(string $fullPath): string
    {
        $filterChain = new FilterChain();
        $filterChain->attach(new DashToCamelCase());
        $filterChain->attach(new UnderscoreToCamelCase());

        return ucfirst($filterChain->filter(basename($fullPath)));
    }
}
