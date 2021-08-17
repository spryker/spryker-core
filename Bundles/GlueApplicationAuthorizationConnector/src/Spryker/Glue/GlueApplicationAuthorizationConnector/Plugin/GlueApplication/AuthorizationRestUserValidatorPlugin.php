<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationAuthorizationConnector\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueApplicationAuthorizationConnector\GlueApplicationAuthorizationConnectorFactory getFactory()
 */
class AuthorizationRestUserValidatorPlugin extends AbstractPlugin implements RestUserValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Grabs two transfer objects from RestRequest.HttpRequest.attributes: 'route-authorization-default-configuration' & 'route-authorization-configurations'
     * - If the current request method is in RestRequest.HttpRequest.attributes['route-authorization-configurations'] then that configuration gets used
     * - Otherwise the default authorization configuration gets used and a RouteAuthorizationConfigTransfer is extracted
     * - RouteAuthorizationConfigTransfer.apiCode is required. The API must respond with an API error code if there is any.
     * - RouteAuthorizationConfigTransfer.strategy is required. Without a strategy the authorization check can't be executed.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        return $this->getFactory()->createAuthorizationChecker()->validate($restRequest);
    }
}
