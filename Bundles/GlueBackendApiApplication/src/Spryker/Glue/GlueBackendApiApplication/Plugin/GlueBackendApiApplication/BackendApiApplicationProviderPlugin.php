<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication;

use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class BackendApiApplicationProviderPlugin extends AbstractPlugin implements ApiApplicationProviderPluginInterface
{
    /**
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'backend';

    /**
     * {@inheritDoc}
     * - Returns the name of the GlueBackendApiApplication.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::GLUE_BACKEND_API_APPLICATION;
    }
}
