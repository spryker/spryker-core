<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Finder;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface;

class GlueControllerFinder implements GlueControllerFinderInterface
{
    protected const PATTERN_CONTROLLER_NAMESPACE = '%s\Controller\%s';
    protected const PATTERN_CONTROLLER_FILENAME = '%s.php';
    protected const PATTERN_PLUGIN = '\Plugin\\';

    protected const CONTROLLER_SUFFIX = 'Controller';

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface
     */
    protected $inflector;

    /**
     * @var array
     */
    protected $sourceDirectories;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface $finder
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToTextInflectorInterface $inflector
     * @param array $sourceDirectories
     */
    public function __construct(
        RestApiDocumentationGeneratorToFinderInterface $finder,
        RestApiDocumentationGeneratorToTextInflectorInterface $inflector,
        array $sourceDirectories
    ) {
        $this->finder = $finder;
        $this->inflector = $inflector;
        $this->sourceDirectories = $sourceDirectories;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return \SplFileInfo[]
     */
    public function getGlueControllerFilesFromPlugin(ResourceRoutePluginInterface $plugin): array
    {
        $controllerNamespace = $this->getPluginControllerClass($plugin);
        $controllerNamespaceExploded = explode('\\', $controllerNamespace);

        $existingDirectories = $this->getControllerSourceDirectories(array_slice($controllerNamespaceExploded, -3)[0]);
        if (!$existingDirectories) {
            return [];
        }

        $finder = clone $this->finder;
        $finder->in($existingDirectories)->name(sprintf(static::PATTERN_CONTROLLER_FILENAME, end($controllerNamespaceExploded)));

        return iterator_to_array($finder);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return string
     */
    protected function getPluginControllerClass(ResourceRoutePluginInterface $plugin): string
    {
        $controllerClass = $this->inflector->classify($plugin->getController()) . static::CONTROLLER_SUFFIX;
        $pluginClass = get_class($plugin);
        $moduleNamespace = substr($pluginClass, 0, strpos($pluginClass, static::PATTERN_PLUGIN));

        return sprintf(
            static::PATTERN_CONTROLLER_NAMESPACE,
            $moduleNamespace,
            $controllerClass
        );
    }

    /**
     * @param string $moduleName
     *
     * @return string[]
     */
    protected function getControllerSourceDirectories(string $moduleName): array
    {
        $directories = array_map(function ($directory) use ($moduleName) {
            return sprintf($directory, $moduleName);
        }, $this->sourceDirectories);

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
