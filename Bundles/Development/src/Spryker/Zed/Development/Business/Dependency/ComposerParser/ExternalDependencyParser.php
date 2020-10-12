<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\ComposerParser;

use ReflectionClass;
use Spryker\Zed\Development\DevelopmentConfig;
use Throwable;

class ExternalDependencyParser implements ExternalDependencyParserInterface
{
    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var string[]
     */
    protected $resolvedComposerNameByClassNameMap = [];

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $className
     *
     * @return string|null
     */
    public function findPackageNameByNamespace(string $className): ?string
    {
        if (isset($this->resolvedComposerNameByClassNameMap[$className])) {
            return $this->resolvedComposerNameByClassNameMap[$className];
        }

        if ($this->isPhpInternalClass($className)) {
            return null;
        }

        try {
            $reflectionClass = new ReflectionClass($className);
        } catch (Throwable $throwable) {
            return null;
        }

        $pathFragments = explode(DIRECTORY_SEPARATOR, $reflectionClass->getFileName());
        $vendorPosition = array_search('vendor', $pathFragments);

        if ($vendorPosition === false) {
            return null;
        }

        $vendorNameAndPackageName = array_slice($pathFragments, $vendorPosition + 1, 2);

        $composerName = implode('/', $vendorNameAndPackageName);

        $composerName = $this->mapExternalToInternalPackageName($composerName);

        $this->resolvedComposerNameByClassNameMap[$className] = $composerName;

        return $composerName;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function isPhpInternalClass(string $className): bool
    {
        if (strpos($className, '\\') === false) {
            return true;
        }

        return false;
    }

    /**
     * @param string $composerName
     *
     * @return string
     */
    protected function mapExternalToInternalPackageName(string $composerName): string
    {
        $map = $this->config->getExternalToInternalMap();

        if (isset($map[$composerName])) {
            return $map[$composerName];
        }

        return $composerName;
    }
}
