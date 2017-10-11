<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator;

use Exception;
use ReflectionClass;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

class PackageNameHydrator implements DependencyHydratorInterface
{

    /**
     * @param array $dependency
     *
     * @return void
     */
    public function hydrate(array &$dependency)
    {
        $dependency['composer name'] = $this->getComposerNameByClassName($dependency);
    }

    /**
     * @param array $dependency
     *
     * @return bool|string
     */
    private function getComposerNameByClassName(array $dependency)
    {
        try {
            $reflection = new ReflectionClass($dependency[DependencyTree::META_FOREIGN_CLASS_NAME]);
            $filePath = $reflection->getFileName();

            $this->cleanAutoloader();

            $relativeFilePath = str_replace(APPLICATION_VENDOR_DIR, '', $filePath);

            $pathParts = explode(DIRECTORY_SEPARATOR, ltrim($relativeFilePath, DIRECTORY_SEPARATOR));

            $path = APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR;

            do {
                $path .= array_shift($pathParts) . DIRECTORY_SEPARATOR;

                $composerPath = $path . 'composer.json';
                if (file_exists($composerPath)) {
                    $composerConfig = json_decode(file_get_contents($composerPath));

                    return $composerConfig->name;
                }
            } while (count($pathParts) > 0);
        } catch (Exception $e) {
        }

        return false;
    }

    /**
     * PHP_CodeSniffer adds an autoloader to the autoloader stack. This method make sure that it's removed after it was added.
     *
     * @return void
     */
    private function cleanAutoloader()
    {
        $autoloadFunctions = spl_autoload_functions();
        $codeSnifferAutoloadFunction = false;

        foreach ($autoloadFunctions as $autoloadFunction) {
            if (is_array($autoloadFunction) && $autoloadFunction[0] === 'PHP_CodeSniffer') {
                $codeSnifferAutoloadFunction = $autoloadFunction;
            }
        }

        if ($codeSnifferAutoloadFunction) {
            spl_autoload_unregister($codeSnifferAutoloadFunction);
        }
    }

}
