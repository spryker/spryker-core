<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder;

use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface;

class RestRequestValidatorSchemaFinder implements RestRequestValidatorSchemaFinderInterface
{
    /**
     * @var string
     */
    protected $fileNamePattern;

    /**
     * @var array
     */
    protected $pathPattern;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface $finder
     * @param array $pathPattern
     * @param string $fileNamePattern
     */
    public function __construct(RestRequestValidatorToFinderAdapterInterface $finder, array $pathPattern, string $fileNamePattern)
    {
        $this->finder = $finder;
        $this->pathPattern = $pathPattern;
        $this->fileNamePattern = $fileNamePattern;
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    public function findSchemas(): RestRequestValidatorToFinderAdapterInterface
    {
        $this->finder
            ->in($this->getPaths())
            ->name($this->fileNamePattern);

        return $this->finder;
    }

    /**
     * @return string[]
     */
    protected function getPaths(): array
    {
        $paths = [];
        foreach ($this->pathPattern as $pathPattern) {
            $paths = array_merge($paths, glob($pathPattern));
        }

        return $paths;
    }
}
