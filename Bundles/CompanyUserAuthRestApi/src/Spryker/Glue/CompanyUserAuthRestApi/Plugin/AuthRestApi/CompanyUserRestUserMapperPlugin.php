<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Plugin\AuthRestApi;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserMapperPluginInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiFactory getFactory()
 */
class CompanyUserRestUserMapperPlugin extends AbstractPlugin implements RestUserMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps company user data to the rest user identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    public function map(RestUserTransfer $restUserTransfer, RestRequestInterface $restRequest): RestUserTransfer
    {
        return $this->getFactory()
            ->createRestUserMapper()
            ->map($restUserTransfer, $restRequest);
    }
}
