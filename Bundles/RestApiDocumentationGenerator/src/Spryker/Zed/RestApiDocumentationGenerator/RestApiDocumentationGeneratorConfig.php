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
    public const SWAGGER_GENERATOR_FILE_NAME = 'spryker_rest_api';
    public const SWAGGER_GENERATOR_INFO_VERSION = '1.0.0';
    public const SWAGGER_GENERATOR_INFO_TITLE = 'Spryker API';
    public const SWAGGER_GENERATOR_INFO_LICENSE_NAME = 'MIT';

    /**
     * @return string
     */
    public function getGeneratedFileName(): string
    {
        return static::SWAGGER_GENERATOR_FILE_NAME;
    }

    /**
     * @return string
     */
    public function getInfoApiVersion(): string
    {
        return static::SWAGGER_GENERATOR_INFO_VERSION;
    }

    /**
     * @return string
     */
    public function getInfoApiTitle(): string
    {
        return static::SWAGGER_GENERATOR_INFO_TITLE;
    }

    /**
     * @return string
     */
    public function getInfoApiInfoLicenceName(): string
    {
        return static::SWAGGER_GENERATOR_INFO_LICENSE_NAME;
    }

    /**
     * @return string
     */
    public function getRestApplicationDomain(): string
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_DOMAIN);
    }
}
