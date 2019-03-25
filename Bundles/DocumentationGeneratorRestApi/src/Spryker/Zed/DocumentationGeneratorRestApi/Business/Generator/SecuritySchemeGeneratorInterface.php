<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

/**
 * Term "security scheme" is used in accordance to official OpenApi specification
 * (see https://swagger.io/docs/specification/authentication/)
 */
interface SecuritySchemeGeneratorInterface
{
    /**
     * @return array
     */
    public function getSecuritySchemes(): array;
}
