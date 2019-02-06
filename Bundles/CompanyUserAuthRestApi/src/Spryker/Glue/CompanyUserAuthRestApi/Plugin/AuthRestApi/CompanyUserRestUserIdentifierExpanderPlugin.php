<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Plugin\AuthRestApi;

use Generated\Shared\Transfer\RestUserIdentifierTransfer;
use Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserIdentifierExpanderPluginInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiFactory getFactory()
 */
class CompanyUserRestUserIdentifierExpanderPlugin extends AbstractPlugin implements RestUserIdentifierExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands rest user identifier with company user data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestUserIdentifierTransfer $restUserIdentifierTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserIdentifierTransfer
     */
    public function expand(RestUserIdentifierTransfer $restUserIdentifierTransfer, RestRequestInterface $restRequest): RestUserIdentifierTransfer
    {
        return $this->getFactory()
            ->createRestUserIdentifierExpander()
            ->expand($restUserIdentifierTransfer, $restRequest);
    }
}
