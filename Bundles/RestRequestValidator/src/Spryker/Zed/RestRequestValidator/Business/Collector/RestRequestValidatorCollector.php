<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Collector;

use Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface;
use Spryker\Zed\RestRequestValidator\Business\Exception\SchemaFileNotFound;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface;

class RestRequestValidatorCollector implements RestRequestValidatorCollectorInterface
{
    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface
     */
    protected $validationSchemaFinder;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface
     */
    protected $yaml;

    /**
     * @var \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface
     */
    protected $filesystem;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface $validationSchemaFinder
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface $filesystem
     * @param \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface $yaml
     */
    public function __construct(
        RestRequestValidatorSchemaFinderInterface $validationSchemaFinder,
        RestRequestValidatorToFilesystemAdapterInterface $filesystem,
        RestRequestValidatorToYamlAdapterInterface $yaml
    ) {
        $this->validationSchemaFinder = $validationSchemaFinder;
        $this->filesystem = $filesystem;
        $this->yaml = $yaml;
    }

    /**
     * @throws \Spryker\Zed\RestRequestValidator\Business\Exception\SchemaFileNotFound
     *
     * @return array
     */
    public function collect(): array
    {
        $resultingConfig = [];

        foreach ($this->validationSchemaFinder->findSchemas() as $moduleValidationSchema) {
            if (!$this->filesystem->exists($moduleValidationSchema->getPathname())) {
                throw new SchemaFileNotFound('Schema-File does not exist: ' . $moduleValidationSchema);
            }
            $parsedConfiguration = $this->yaml->parseFile($moduleValidationSchema->getPathname());
            foreach ($parsedConfiguration as $resourceName => $resourceValidatorConfiguration) {
                $resultingConfig[$resourceName][] = $resourceValidatorConfiguration;
            }
        }

        return $resultingConfig;
    }
}
