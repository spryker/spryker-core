<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Plugin\GlueBackendApiApplicationAuthorizationConnector;

use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Spryker\Zed\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\ProtectedPathCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiFactory getFactory()
 */
class DynamicEntityProtectedPathCollectionExpanderPlugin extends AbstractPlugin implements ProtectedPathCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands a list of protected endpoints with dynamic entity endpoints.
     *
     * @api
     *
     * @param array<string, mixed> $protectedPathCollection
     *
     * @return array<string, mixed>
     */
    public function expand(array $protectedPathCollection): array
    {
        return $this->getFactory()->createDynamicEntityProtectedPathCollectionExpander()->expand($protectedPathCollection);
    }
}
