<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi;

use Spryker\Shared\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConstants;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DocumentationGeneratorRestApiConfig extends AbstractBundleConfig
{
    public const GENERATED_FILE_OUTPUT_DIRECTORY = APPLICATION_SOURCE_DIR . '/Generated/Glue/Specification/';
    public const GENERATED_FILE_PREFIX = 'spryker_rest_api';

    public const REST_API_DOCUMENTATION_INFO_VERSION = '1.0.0';
    public const REST_API_DOCUMENTATION_INFO_TITLE = 'Spryker API';
    public const REST_API_DOCUMENTATION_INFO_LICENSE_NAME = 'MIT';

    protected const APPLICATION_PROJECT_ANNOTATION_SOURCE_DIRECTORY_PATTERN = '/Glue/%1$s/Controller/';
    protected const APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_PATTERN = '/*/*/src/*/Glue/%1$s/Controller/';

    /**
     * @return string
     */
    public function getGeneratedFileOutputDirectory(): string
    {
        return static::GENERATED_FILE_OUTPUT_DIRECTORY;
    }

    /**
     * @return string
     */
    public function getGeneratedFilePrefix(): string
    {
        return static::GENERATED_FILE_PREFIX;
    }

    /**
     * @return string
     */
    public function getApiDocumentationVersionInfo(): string
    {
        return static::REST_API_DOCUMENTATION_INFO_VERSION;
    }

    /**
     * @return string
     */
    public function getApiDocumentationTitleInfo(): string
    {
        return static::REST_API_DOCUMENTATION_INFO_TITLE;
    }

    /**
     * @return string
     */
    public function getApiDocumentationLicenceNameInfo(): string
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
    public function getAnnotationSourceDirectories(): array
    {
        return array_merge(
            $this->getCoreAnnotationSourceDirectoryPatterns(),
            $this->getProjectAnnotationSourceDirectoryPatterns()
        );
    }

    /**
     * @return array
     */
    protected function getCoreAnnotationSourceDirectoryPatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . static::APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_PATTERN,
        ];
    }

    /**
     * @return array
     */
    protected function getProjectAnnotationSourceDirectoryPatterns(): array
    {
        return [
            APPLICATION_SOURCE_DIR . '/' . $this->get(KernelConstants::PROJECT_NAMESPACE) . static::APPLICATION_PROJECT_ANNOTATION_SOURCE_DIRECTORY_PATTERN,
        ];
    }

    /**
     * @return bool
     */
    public function isRestApiDocumentationGeneratorEnabled(): bool
    {
        return $this->get(DocumentationGeneratorRestApiConstants::ENABLE_REST_API_DOCUMENTATION_GENERATION, $this->getDocumentationGeneratorRestApiDefaultValue());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    protected function getDocumentationGeneratorRestApiDefaultValue(): bool
    {
        return APPLICATION_ENV === 'development' || APPLICATION_ENV === 'devtest';
    }
}
