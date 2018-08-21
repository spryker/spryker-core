<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business;

interface RestApiDocumentationGeneratorFacadeInterface
{
    /**
     * Specification:
     *  - Generates Open API specification file in YAML format
     *
     * @api
     *
     * @return void
     */
    public function generateOpenApiSpecification(): void;
}
