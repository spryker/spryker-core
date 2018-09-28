<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator;

use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class RestApiDocumentationGeneratorConfig extends AbstractBundleConfig
{
    public const REST_API_DOCUMENTATION_TARGET_DIRECTORY = APPLICATION_SOURCE_DIR . '/Generated/Glue/Specification/';
    public const REST_API_DOCUMENTATION_FILE_NAME = 'spryker_rest_api';
    public const REST_API_DOCUMENTATION_INFO_VERSION = '1.0.0';
    public const REST_API_DOCUMENTATION_INFO_TITLE = 'Spryker API';
    public const REST_API_DOCUMENTATION_INFO_LICENSE_NAME = 'MIT';

    public const ANNOTATION_KEY_GET_COLLECTION = 'getCollection';
    public const ANNOTATION_KEY_GET_RESOURCE = 'getResource';
    public const ANNOTATION_KEY_HEADERS = 'headers';
    public const ANNOTATION_KEY_RESPONSES = 'responses';
    public const ANNOTATION_KEY_SUMMARY = 'summary';

    protected const APPLICATION_SOURCE_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN = '/Glue/%1$s/Controller/';
    protected const APPLICATION_VENDOR_SOURCE_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN = '/*/*/src/*/Glue/%1$s/Controller/';

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
        return array_merge(
            $this->getCoreAnnotationsSourceDirectoryPatterns(),
            $this->getApplicationAnnotationsSourceDirectoryPattern()
        );
    }

    /**
     * @return array
     */
    protected function getCoreAnnotationsSourceDirectoryPatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . static::APPLICATION_VENDOR_SOURCE_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN,
        ];
    }

    /**
     * @return array
     */
    protected function getApplicationAnnotationsSourceDirectoryPattern(): array
    {
        return [
            APPLICATION_SOURCE_DIR . '/' . $this->get(KernelConstants::PROJECT_NAMESPACE) . static::APPLICATION_SOURCE_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN,
        ];
    }
}
