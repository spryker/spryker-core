<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;

class ClassHelper extends Module
{
    use VirtualFilesystemHelperTrait;

    /**
     * Use this method when you need to mock `class_exists`.
     *
     * @param string $className
     * @param string|null $extends
     * @param array|null $implements
     *
     * @return void
     */
    public function createAutoloadableClass(string $className, ?string $extends = null, ?array $implements = null): void
    {
        $className = ltrim($className, '\\');
        $classNameFragments = explode('\\', $className);

        $extends = ($extends !== null) ? ' extends ' . $extends : '';
        $implements = ($implements !== null) ? ' implements ' . implode(', ', $implements) : '';

        $classNameShort = array_pop($classNameFragments);
        $namespace = implode('\\', $classNameFragments);
        $classContent = sprintf('<?php namespace %s; class %s%s%s {}', $namespace, $classNameShort, $extends, $implements);

        $virtualDirectory = $this->getVirtualFilesystemHelper()->getVirtualDirectory();

        $fileName = $virtualDirectory . str_replace('\\', '_', $className) . '.php';

        file_put_contents($fileName, $classContent);

        require_once $fileName;
    }
}
