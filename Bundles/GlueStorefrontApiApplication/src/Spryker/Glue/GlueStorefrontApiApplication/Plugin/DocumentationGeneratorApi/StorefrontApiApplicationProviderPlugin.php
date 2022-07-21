<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\DocumentationGeneratorApi;

use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class StorefrontApiApplicationProviderPlugin extends AbstractPlugin implements ApiApplicationProviderPluginInterface
{
    /**
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'storefront';

    /**
     * {@inheritDoc}
     * - Returns the name of the GlueStorefrontApiApplication.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::GLUE_STOREFRONT_API_APPLICATION;
    }
}
