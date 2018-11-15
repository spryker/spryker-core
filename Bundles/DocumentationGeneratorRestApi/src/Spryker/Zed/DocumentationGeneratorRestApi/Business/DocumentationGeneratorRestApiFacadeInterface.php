<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business;

interface DocumentationGeneratorRestApiFacadeInterface
{
    /**
     * Specification:
     *  - Generates documentation for enabled resources.
     *  - Documentation is generated in OpenAPI Specification format.
     *  - Saves documentation to .yml file.
     *
     * @api
     *
     * @return void
     */
    public function generateDocumentation(): void;
}
