<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SwaggerGenerator\Business;

interface SwaggerGeneratorFacadeInterface
{
    /**
     * Specification:
     *  - Generates Open API specification file in YAML format (Name of generated file declaring in configuration file)
     *
     * @api
     *
     * @return void
     */
    public function generate(): void;
}
