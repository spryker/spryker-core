<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface;

class ResourceRelationshipPluginFinder implements ResourceRelationshipPluginFinderInterface
{
    protected const PATTERN_PLUGIN_FILENAME = '%s.php';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    protected $finder;

    /**
     * @var array
     */
    protected $sourceDirectories;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface $finder
     * @param array $sourceDirectories
     */
    public function __construct(
        DocumentationGeneratorRestApiToFinderInterface $finder,
        array $sourceDirectories
    ) {
        $this->finder = $finder;
        $this->sourceDirectories = $sourceDirectories;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $plugin
     *
     * @return \SplFileInfo[]
     */
    public function getPluginFilesFromPlugin(ResourceRelationshipPluginInterface $plugin): array
    {
        $pluginClass = get_class($plugin);
        $pluginNamespaceExploded = explode('\\', $pluginClass);

        $existingDirectories = $this->getPluginSourceDirectories(array_slice($pluginNamespaceExploded, -3)[0]);

        if (!$existingDirectories) {
            return [];
        }

        $finder = clone $this->finder;
        $finder->in($existingDirectories)->name(sprintf(static::PATTERN_PLUGIN_FILENAME, end($pluginNamespaceExploded)));

        return iterator_to_array($finder);
    }

    /**
     * @param string $moduleName
     *
     * @return string[]
     */
    protected function getPluginSourceDirectories(string $moduleName): array
    {
        $directories = [];
        foreach ($this->sourceDirectories as $directory) {
            $directories[] = sprintf($directory, $moduleName);
        }

        return $this->getExistingSourceDirectories($directories);
    }

    /**
     * @param array $dirs
     *
     * @return string[]
     */
    protected function getExistingSourceDirectories(array $dirs): array
    {
        return array_filter($dirs, function ($directory) {
            return (bool)glob($directory, GLOB_ONLYDIR);
        });
    }
}
