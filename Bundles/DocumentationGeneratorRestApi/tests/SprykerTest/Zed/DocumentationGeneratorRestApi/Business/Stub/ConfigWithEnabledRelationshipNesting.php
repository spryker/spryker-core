<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub;

use Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig;

class ConfigWithEnabledRelationshipNesting extends DocumentationGeneratorRestApiConfig
{
    /**
     * @return bool
     */
    public function isNestedRelationshipsEnabled(): bool
    {
        return true;
    }
}
