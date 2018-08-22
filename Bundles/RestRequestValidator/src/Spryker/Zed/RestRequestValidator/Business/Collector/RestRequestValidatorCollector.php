<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Collector;

use Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface;
use Spryker\Zed\RestRequestValidator\Business\Exception\SchemaFileNotFound;
use Symfony\Component\Yaml\Yaml;

class RestRequestValidatorCollector implements RestRequestValidatorCollectorInterface
{
    /**
     * @var \Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface
     */
    protected $validationSchemaFinder;

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder\RestRequestValidatorSchemaFinderInterface $validationSchemaFinder
     */
    public function __construct(RestRequestValidatorSchemaFinderInterface $validationSchemaFinder)
    {
        $this->validationSchemaFinder = $validationSchemaFinder;
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
            if (!file_exists($moduleValidationSchema->getPathname())) {
                throw new SchemaFileNotFound('Schema-File does not exist: ' . $moduleValidationSchema);
            }
            $parsedConfiguration = Yaml::parseFile($moduleValidationSchema->getPathname());
            foreach ($parsedConfiguration as $resourceName => $resourceValidatorConfiguration) {
                $resultingConfig[$resourceName][] = $resourceValidatorConfiguration;
            }
        }

        return $resultingConfig;
    }
}
