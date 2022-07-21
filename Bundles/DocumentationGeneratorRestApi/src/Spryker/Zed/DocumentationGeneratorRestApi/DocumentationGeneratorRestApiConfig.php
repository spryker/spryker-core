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

    /**
     * @var string
     */
    public const GENERATED_FILE_PREFIX = 'spryker_rest_api';

    /**
     * @var string
     */
    public const REST_API_DOCUMENTATION_INFO_VERSION = '1.0.0';

    /**
     * @var string
     */
    public const REST_API_DOCUMENTATION_INFO_TITLE = 'Spryker API';

    /**
     * @var string
     */
    public const REST_API_DOCUMENTATION_INFO_LICENSE_NAME = 'MIT';

    /**
     * @var string
     */
    protected const REST_API_DOCUMENTATION_CONTACT_NAME = 'Spryker';

    /**
     * @var string
     */
    protected const REST_API_DOCUMENTATION_CONTACT_URL = 'https://support.spryker.com/';

    /**
     * @var string
     */
    protected const REST_API_DOCUMENTATION_CONTACT_EMAIL = 'support@spryker.com';

    /**
     * @var string
     */
    protected const APPLICATION_PROJECT_ANNOTATION_SOURCE_DIRECTORY_PATTERN = '/Glue/%1$s/Controller/';

    /**
     * @var string
     */
    protected const APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_PATTERN = '/*/*/src/*/Glue/%1$s/Controller/';

    /**
     * @api
     *
     * @return string
     */
    public function getGeneratedFileOutputDirectory(): string
    {
        return static::GENERATED_FILE_OUTPUT_DIRECTORY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getGeneratedFilePrefix(): string
    {
        return static::GENERATED_FILE_PREFIX;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getApiDocumentationVersionInfo(): string
    {
        return static::REST_API_DOCUMENTATION_INFO_VERSION;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getApiDocumentationContactName(): string
    {
        return static::REST_API_DOCUMENTATION_CONTACT_NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getApiDocumentationContactUrl(): string
    {
        return static::REST_API_DOCUMENTATION_CONTACT_URL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getApiDocumentationContactEmail(): string
    {
        return static::REST_API_DOCUMENTATION_CONTACT_EMAIL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getApiDocumentationTitleInfo(): string
    {
        return static::REST_API_DOCUMENTATION_INFO_TITLE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getApiDocumentationLicenceNameInfo(): string
    {
        return static::REST_API_DOCUMENTATION_INFO_LICENSE_NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getRestApplicationDomain(): string
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_DOMAIN);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getAnnotationSourceDirectories(): array
    {
        return array_merge(
            $this->getCoreAnnotationSourceDirectoryPatterns(),
            $this->getProjectAnnotationSourceDirectoryPatterns(),
        );
    }

    /**
     * @return array<string>
     */
    protected function getCoreAnnotationSourceDirectoryPatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . static::APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_PATTERN,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getProjectAnnotationSourceDirectoryPatterns(): array
    {
        return [
            APPLICATION_SOURCE_DIR . '/' . $this->get(KernelConstants::PROJECT_NAMESPACE) . static::APPLICATION_PROJECT_ANNOTATION_SOURCE_DIRECTORY_PATTERN,
        ];
    }

    /**
     * @api
     *
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

    /**
     * Specification:
     * - Overwrite this to true if API version resolving should happen to all endpoints via the first part of the path
     * - e.g /1/resource1 or /v1/resource2 instead of header value
     *
     * @uses \Spryker\Glue\GlueApplication\GlueApplicationConfig::getPathVersionResolving
     *
     * @api
     *
     * @return bool
     */
    public function getPathVersionResolving(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Set this to the value you want to be the prefix of the version in the URL (if any)
     * - In the default setting, it will not exist, but if it is set to "v" then all versionable resources will have
     * - a "v" as a prefix to their version in the URL. e.g. /v1/resource
     *
     * @uses \Spryker\Glue\GlueApplication\GlueApplicationConfig::getPathVersionPrefix()
     *
     * @api
     *
     * @return string
     */
    public function getPathVersionPrefix(): string
    {
        return '';
    }

    /**
     * Specification:
     * - Defines if nested relationships displaying is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isNestedRelationshipsEnabled(): bool
    {
        return false;
    }
}
