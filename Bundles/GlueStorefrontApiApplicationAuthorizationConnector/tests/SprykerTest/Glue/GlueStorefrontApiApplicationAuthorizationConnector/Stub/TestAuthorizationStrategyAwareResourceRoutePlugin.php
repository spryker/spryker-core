<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Stub;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\GlueApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationStrategyAwareResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use stdClass;

class TestAuthorizationStrategyAwareResourceRoutePlugin implements AuthorizationStrategyAwareResourceRoutePluginInterface, ResourceInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\RouteAuthorizationConfigTransfer>
     */
    public function getRouteAuthorizationConfigurations(): array
    {
        $routeAuthorizationConfigTransfer = (new RouteAuthorizationConfigTransfer())->setStrategy('test');

        return [
            'get' => $routeAuthorizationConfigTransfer,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return callable
     */
    public function getResource(GlueRequestTransfer $glueRequestTransfer): callable
    {
        return [
            new stdClass(),
            'method',
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'test';
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return 'FooController';
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        return new GlueResourceMethodCollectionTransfer();
    }
}
