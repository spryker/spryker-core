<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator;

use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class RestApiDocumentationGeneratorConfig extends AbstractBundleConfig
{
    public const REST_API_DOCUMENTATION_TARGET_DIRECTORY = APPLICATION_SOURCE_DIR . '/Generated/Glue/Specification/';
    public const REST_API_DOCUMENTATION_FILE_NAME = 'spryker_rest_api';
    public const REST_API_DOCUMENTATION_INFO_VERSION = '1.0.0';
    public const REST_API_DOCUMENTATION_INFO_TITLE = 'Spryker API';
    public const REST_API_DOCUMENTATION_INFO_LICENSE_NAME = 'MIT';

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return static::REST_API_DOCUMENTATION_TARGET_DIRECTORY;
    }

    /**
     * @return string
     */
    public function getGeneratedFileName(): string
    {
        return static::REST_API_DOCUMENTATION_FILE_NAME;
    }

    /**
     * @return string
     */
    public function getInfoApiVersion(): string
    {
        return static::REST_API_DOCUMENTATION_INFO_VERSION;
    }

    /**
     * @return string
     */
    public function getInfoApiTitle(): string
    {
        return static::REST_API_DOCUMENTATION_INFO_TITLE;
    }

    /**
     * @return string
     */
    public function getInfoApiInfoLicenceName(): string
    {
        return static::REST_API_DOCUMENTATION_INFO_LICENSE_NAME;
    }

    /**
     * @return string
     */
    public function getRestApplicationDomain(): string
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_DOMAIN);
    }

    /**
     * @return array
     */
    public function getAnnotationsSourceDirectories(): array
    {
        return array_merge($this->getCoreAnnotationsSourceDirectoryGlobPatterns(), $this->getApplicationAnnotationsSourceDirectoryGlobPattern());
    }

    /**
     * @return array
     */
    protected function getCoreAnnotationsSourceDirectoryGlobPatterns(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getApplicationAnnotationsSourceDirectoryGlobPattern(): array
    {
        return [];
    }
}
