<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin;

/**
 * Allows to define API application to generate documentation.
 */
interface ApiApplicationProviderPluginInterface
{
    /**
     * Specification:
     * - Returns the name of the API application.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;
}
