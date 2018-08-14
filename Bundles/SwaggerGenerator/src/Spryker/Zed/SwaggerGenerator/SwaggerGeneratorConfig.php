<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SwaggerGenerator;

use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SwaggerGeneratorConfig extends AbstractBundleConfig
{
    public const SWAGGER_GENERATOR_FILE_NAME = 'SWAGGER_GENERATOR_FILE_NAME';
    public const SWAGGER_GENERATOR_INFO_VERSION = 'SWAGGER_GENERATOR_INFO_VERSION';
    public const SWAGGER_GENERATOR_INFO_TITLE = 'SWAGGER_GENERATOR_INFO_TITLE';
    public const SWAGGER_GENERATOR_INFO_LICENCE_NAME = 'SWAGGER_GENERATOR_INFO_LICENCE_NAME';

    /**
     * @return string
     */
    public function getGeneratedFileName(): string
    {
        return $this->get(static::SWAGGER_GENERATOR_FILE_NAME);
    }

    /**
     * @return string
     */
    public function getInfoApiVersion(): string
    {
        return $this->get(static::SWAGGER_GENERATOR_INFO_VERSION);
    }

    /**
     * @return string
     */
    public function getInfoApiTitle(): string
    {
        return $this->get(static::SWAGGER_GENERATOR_INFO_TITLE);
    }

    /**
     * @return string
     */
    public function getInfoApiInfoLicenceName(): string
    {
        return $this->get(static::SWAGGER_GENERATOR_INFO_LICENCE_NAME);
    }

    /**
     * @return string
     */
    public function getRestApplicationDomain()
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_DOMAIN);
    }
}
