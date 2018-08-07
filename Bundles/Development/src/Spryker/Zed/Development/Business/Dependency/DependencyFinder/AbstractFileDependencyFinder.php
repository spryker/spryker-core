<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

abstract class AbstractFileDependencyFinder implements DependencyFinderInterface
{
    /**
     * @param string $module
     *
     * @return bool
     */
    protected function isExtensionModule(string $module): bool
    {
        return preg_match('/Extension$/', $module);
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    protected function isPluginFile(string $file): bool
    {
        return (strpos($file, '/Plugin/') !== false);
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    protected function isTestFile($file)
    {
        return !strpos($file, '/src/');
    }
}
