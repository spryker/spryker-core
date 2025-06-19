<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Finder;

use Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External\DocumentationGeneratorOpenApiToFinderInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig;

class GlueFileFinder implements FinderInterface
{
    /**
     * @var string
     */
    protected const PATTERN_FILENAME = '%s.php';

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External\DocumentationGeneratorOpenApiToFinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig
     */
    protected $documentationGeneratorOpenApiConfig;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External\DocumentationGeneratorOpenApiToFinderInterface $finder
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig $documentationGeneratorOpenApiConfig
     */
    public function __construct(
        DocumentationGeneratorOpenApiToFinderInterface $finder,
        DocumentationGeneratorOpenApiConfig $documentationGeneratorOpenApiConfig
    ) {
        $this->finder = $finder;
        $this->documentationGeneratorOpenApiConfig = $documentationGeneratorOpenApiConfig;
    }

    /**
     * @param string $classPath
     *
     * @return array<\SplFileInfo>
     */
    public function getFilesFromClassPath(string $classPath): array
    {
        $namespaceExploded = explode('\\', $classPath);

        $candidateDepths = [3, 4];

        foreach ($candidateDepths as $depth) {
            $existingDirectories = $this->getSourceDirectories(array_slice($namespaceExploded, -$depth)[0]);
            if ($existingDirectories !== []) {
                break;
            }
        }
        if (!$existingDirectories) {
            return [];
        }

        $finder = clone $this->finder;
        $finder->in($existingDirectories)->name(sprintf(static::PATTERN_FILENAME, end($namespaceExploded)));

        return iterator_to_array($finder);
    }

    /**
     * @param string $moduleName
     *
     * @return array<string>
     */
    protected function getSourceDirectories(string $moduleName): array
    {
        $directories = array_map(function ($directory) use ($moduleName) {
            return sprintf($directory, $moduleName);
        }, $this->documentationGeneratorOpenApiConfig->getAnnotationSourceDirectories());

        return $this->getExistingSourceDirectories($directories);
    }

    /**
     * @param array<int, string> $dirs
     *
     * @return array<string>
     */
    protected function getExistingSourceDirectories(array $dirs): array
    {
        return array_filter($dirs, function ($directory) {
            return (bool)glob($directory, GLOB_ONLYDIR | GLOB_NOSORT);
        });
    }
}
