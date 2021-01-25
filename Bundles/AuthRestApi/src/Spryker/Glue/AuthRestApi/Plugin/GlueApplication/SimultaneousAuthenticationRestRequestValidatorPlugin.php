<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiFactory getFactory()
 */
class SimultaneousAuthenticationRestRequestValidatorPlugin extends AbstractPlugin implements RestRequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates request for Authentication and X-Anonymous-Customer-Unique-Id headers being provided simultaneously.
     * - Will return RestErrorCollectionTransfer with error in case both headers are present in the request.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        return $this->getFactory()
            ->createSimultaneousAuthenticationRestRequestValidator()
            ->validate($httpRequest, $restRequest);
    }
}
