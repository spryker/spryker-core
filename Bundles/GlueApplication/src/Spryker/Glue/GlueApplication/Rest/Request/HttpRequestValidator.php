<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Symfony\Component\HttpFoundation\Request;

class HttpRequestValidator implements HttpRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[]
     */
    protected $requestValidatorPlugins = [];

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    protected $resourceRouteLoader;

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\HeadersHttpRequestValidatorInterface
     */
    protected $headersHttpRequestValidator;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[] $requestValidatorPlugins
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface $resourceRouteLoader
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $config
     * @param \Spryker\Glue\GlueApplication\Rest\Request\HeadersHttpRequestValidatorInterface $headersHttpRequestValidator
     */
    public function __construct(
        array $requestValidatorPlugins,
        ResourceRouteLoaderInterface $resourceRouteLoader,
        GlueApplicationConfig $config,
        HeadersHttpRequestValidatorInterface $headersHttpRequestValidator
    ) {
        $this->requestValidatorPlugins = $requestValidatorPlugins;
        $this->resourceRouteLoader = $resourceRouteLoader;
        $this->config = $config;
        $this->headersHttpRequestValidator = $headersHttpRequestValidator;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        if ($this->config->getValidateRequestHeaders()) {
            $restErrorMessageTransfer = $this->headersHttpRequestValidator->validate($request);

            if ($restErrorMessageTransfer) {
                return $restErrorMessageTransfer;
            }
        }

        foreach ($this->requestValidatorPlugins as $requestValidatorPlugin) {
            $restErrorMessageTransfer = $requestValidatorPlugin->validate($request);
            if (!$restErrorMessageTransfer) {
                continue;
            }

            return $restErrorMessageTransfer;
        }

        return null;
    }
}
