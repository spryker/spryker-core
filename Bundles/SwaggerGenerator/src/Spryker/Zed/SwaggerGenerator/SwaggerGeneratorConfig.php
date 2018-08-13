<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SwaggerGenerator;

use Spryker\Shared\SwaggerGenerator\SwaggerGeneratorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SwaggerGeneratorConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getGeneratedFileName(): string
    {
        return $this->get(SwaggerGeneratorConstants::SWAGGER_GENERATOR_FILE_NAME);
    }
}
