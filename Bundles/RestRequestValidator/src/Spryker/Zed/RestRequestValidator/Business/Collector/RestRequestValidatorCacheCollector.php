<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Collector;

use Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface;

class RestRequestValidatorCacheCollector implements RestRequestValidatorCacheCollectorInterface
{
    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface
     */
    protected $restRequestValidatorSchemaFinder;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface
     */
    protected $yaml;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface
     */
    protected $filesystem;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface $restRequestValidatorSchemaFinder
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface $filesystem
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface $yaml
     */
    public function __construct(
        RestRequestValidatorSchemaFinderInterface $restRequestValidatorSchemaFinder,
        RestRequestValidatorToFilesystemAdapterInterface $filesystem,
        RestRequestValidatorToYamlAdapterInterface $yaml
    ) {
        $this->restRequestValidatorSchemaFinder = $restRequestValidatorSchemaFinder;
        $this->filesystem = $filesystem;
        $this->yaml = $yaml;
    }

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function collect(string $storeName): array
    {
        $resultingConfig = [];

        $paths = $this->restRequestValidatorSchemaFinder->getPaths($storeName);
        if (!$paths) {
            return $resultingConfig;
        }

        foreach ($this->restRequestValidatorSchemaFinder->findSchemas($paths) as $moduleValidationSchema) {
            $parsedConfiguration = $this->yaml->parseFile($moduleValidationSchema->getPathname());
            foreach ($parsedConfiguration as $resourceName => $resourceValidatorConfiguration) {
                $resultingConfig[$resourceName][] = $resourceValidatorConfiguration;
            }
        }

        return $resultingConfig;
    }
}
