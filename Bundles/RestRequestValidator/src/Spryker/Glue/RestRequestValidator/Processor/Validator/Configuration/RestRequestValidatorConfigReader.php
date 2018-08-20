<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration;

use Spryker\Glue\RestRequestValidator\Business\Exception\CacheFileNotFound;
use Spryker\Shared\RestRequestValidator\RestRequestValidatorConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class RestRequestValidatorConfigReader implements RestRequestValidatorConfigReaderInterface
{
    /**
     * @param string $resourceType
     *
     * @throws \Spryker\Glue\RestRequestValidator\Business\Exception\CacheFileNotFound
     *
     * @return array
     */
    public function getValidationConfiguration(string $resourceType): array
    {
        $filesystem = new Filesystem();
        if (!$filesystem->exists(RestRequestValidatorConfig::VALIDATION_CACHE_FILENAME_PATTERN)) {
            throw new CacheFileNotFound('Validation cache is enabled, but there is no cache file.');
        }

        $configuration = Yaml::parseFile(RestRequestValidatorConfig::VALIDATION_CACHE_FILENAME_PATTERN);

        if (empty($configuration[$resourceType])) {
            return [];
        }

        return $configuration[$resourceType];
    }
}
