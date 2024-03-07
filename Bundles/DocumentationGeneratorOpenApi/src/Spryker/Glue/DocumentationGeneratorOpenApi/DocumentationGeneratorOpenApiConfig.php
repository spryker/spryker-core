<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\Kernel\KernelConstants;

class DocumentationGeneratorOpenApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const APPLICATION_PROJECT_ANNOTATION_SOURCE_DIRECTORY_CONTROLLER_PATTERN = '/Glue/%1$s/Controller/';

    /**
     * @var string
     */
    protected const APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_CONTROLLER_PATTERN = '/*/*/src/*/Glue/%1$s/Controller/';

    /**
     * @var string
     */
    protected const APPLICATION_PROJECT_ANNOTATION_SOURCE_DIRECTORY_PLUGIN_PATTERN = '/Glue/%1$s/Plugin/';

    /**
     * @var string
     */
    protected const APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_PLUGIN_PATTERN = '/*/*/src/*/Glue/%1$s/Plugin/';

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
    protected function getProjectAnnotationSourceDirectoryPatterns(): array
    {
        return [
            APPLICATION_SOURCE_DIR . DIRECTORY_SEPARATOR . $this->get(KernelConstants::PROJECT_NAMESPACE) . static::APPLICATION_PROJECT_ANNOTATION_SOURCE_DIRECTORY_CONTROLLER_PATTERN,
            APPLICATION_SOURCE_DIR . DIRECTORY_SEPARATOR . $this->get(KernelConstants::PROJECT_NAMESPACE) . static::APPLICATION_PROJECT_ANNOTATION_SOURCE_DIRECTORY_PLUGIN_PATTERN,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getCoreAnnotationSourceDirectoryPatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . static::APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_CONTROLLER_PATTERN,
            APPLICATION_VENDOR_DIR . static::APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_PLUGIN_PATTERN,
        ];
    }
}
