<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class DocumentationGeneratorApiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns file path with generated documentation.
     *
     * @api
     *
     * @param string $applicationName
     *
     * @return string
     */
    public function getGeneratedFullFileName(string $applicationName): string
    {
        return sprintf(
            '%s/src/Generated/Glue%s/Specification/spryker_%s_api.schema.yml',
            APPLICATION_ROOT_DIR,
            ucfirst($applicationName),
            strtolower($applicationName),
        );
    }
}
