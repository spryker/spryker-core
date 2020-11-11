<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Laminas\Filter\FilterChain;
use Laminas\Filter\Word\DashToCamelCase;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Symfony\Component\Finder\SplFileInfo;

class DescriptionUpdater implements UpdaterInterface
{
    public const KEY_DESCRIPTION = 'description';

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile): array
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
