<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

/**
 * Plugin used to let the GlueApplication resources route matcher know about the API application routing.
 */
interface ResourcesProviderPluginInterface
{
    /**
     * Specification:
     * - Returns the applicable API application name.
     *
     * @api
     *
     * @return string
     */
    public function getApplicationName(): string;

    /**
     * Specification:
     * - Returns the stack of `\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface` for the current application.
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    public function getResources(): array;
}
