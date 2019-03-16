<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthCompanyUser\Plugin\AuthRestApi;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserExpanderPluginInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\OauthCompanyUser\OauthCompanyUserFactory getFactory()
 */
class CompanyUserRestUserExpanderPlugin extends AbstractPlugin implements RestUserExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands rest user identifier with company user data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    public function expand(RestUserTransfer $restUserTransfer, RestRequestInterface $restRequest): RestUserTransfer
    {
        return $this->getFactory()
            ->createRestUserExpander()
            ->expand($restUserTransfer, $restRequest);
    }
}
