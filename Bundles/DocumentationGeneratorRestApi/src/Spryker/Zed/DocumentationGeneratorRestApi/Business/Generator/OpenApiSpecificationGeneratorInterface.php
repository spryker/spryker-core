<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

interface OpenApiSpecificationGeneratorInterface
{
    /**
     * @return void
     */
    public function generateOpenApiSpecification(): void;
}
