<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatRequestPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiFactory getFactory()
 */
class CustomerAccessFormatRequestPlugin extends AbstractPlugin implements FormatRequestPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the current accessed resource is restricted by the `spryker/customer-access` module.
     * - Uses mapping defined in the `CustomerAccessRestApiConfig::getCustomerAccessContentTypeToResourceTypeMapping()`.
     * - If the resource is protected, the request attribute `is_protected` is set to `true`.
     * - Does nothing if mapping is not found or the resource is not protected.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function format(RequestBuilderInterface $requestBuilder, Request $request): RequestBuilderInterface
    {
        return $this->getFactory()
            ->createCustomerAccessRequestFormatter()
            ->updateResourceIsProtectedFlag($requestBuilder, $request);
    }
}
