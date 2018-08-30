<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\RestRequestValidator\Business\Exception\CacheFileNotFound;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface;
use Spryker\Shared\RestRequestValidator\RestRequestValidatorConfig;

class RestRequestValidatorConfigReader implements RestRequestValidatorConfigReaderInterface
{
    /**
     * @var \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface
     */
    protected $filesystem;

    /**
     * @var \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface
     */
    protected $yaml;

    /**
     * @param \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapterInterface $filesystem
     * @param \Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapterInterface $yaml
     */
    public function __construct(
        RestRequestValidatorToFilesystemAdapterInterface $filesystem,
        RestRequestValidatorToYamlAdapterInterface $yaml
    ) {
        $this->filesystem = $filesystem;
        $this->yaml = $yaml;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @throws \Spryker\Glue\RestRequestValidator\Business\Exception\CacheFileNotFound
     *
     * @return array
     */
    public function getValidationConfiguration(RestRequestInterface $restRequest): array
    {
        if (!$this->filesystem->exists(APPLICATION_SOURCE_DIR . RestRequestValidatorConfig::VALIDATION_CACHE_FILENAME_PATTERN)) {
            throw new CacheFileNotFound('Validation cache is enabled, but there is no cache file.');
        }

        $configuration = $this->yaml->parseFile(APPLICATION_SOURCE_DIR . RestRequestValidatorConfig::VALIDATION_CACHE_FILENAME_PATTERN);

        if (empty($configuration[$restRequest->getResource()->getType()][strtolower($restRequest->getMetadata()->getMethod())])) {
            return [];
        }

        return $configuration[$restRequest->getResource()->getType()][strtolower($restRequest->getMetadata()->getMethod())];
    }
}
