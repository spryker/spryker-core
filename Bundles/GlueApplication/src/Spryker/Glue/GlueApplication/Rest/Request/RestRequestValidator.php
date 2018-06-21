<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class RestRequestValidator implements RestRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Dependency\Plugin\ValidateRestRequestPluginInterface[]
     */
    protected $restRequestValidatorPlugins = [];

    /**
     * @param \Spryker\Glue\GlueApplication\Dependency\Plugin\ValidateRestRequestPluginInterface[] $restRequestValidatorPlugins
     */
    public function __construct(array $restRequestValidatorPlugins)
    {
        $this->restRequestValidatorPlugins = $restRequestValidatorPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        $restErrorMessageTransfer = $this->validateRequest($restRequest);
        if (!$restErrorMessageTransfer) {
            $restErrorMessageTransfer = $this->executeRestRequestValidatorPlugins($httpRequest, $restRequest);
        }
        return $restErrorMessageTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function validateRequest(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        $method = $restRequest->getMetadata()->getMethod();
        if (!\in_array($method, [Request::METHOD_POST, Request::METHOD_PATCH], true)) {
            return null;
        }

        $restResource = $restRequest->getResource();
        if (!$restResource || !$restResource->getAttributes()) {
            $restErrorMessageTransfer = new RestErrorMessageTransfer();
            $restErrorMessageTransfer->setDetail('Post data missing.');

            return $restErrorMessageTransfer;
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function executeRestRequestValidatorPlugins(
        Request $httpRequest,
        RestRequestInterface $restRequest
    ): ?RestErrorMessageTransfer {

        foreach ($this->restRequestValidatorPlugins as $requestValidatorPlugin) {
            $restErrorMessageTransfer = $requestValidatorPlugin->validate($httpRequest, $restRequest);
            if ($restErrorMessageTransfer) {
                return $restErrorMessageTransfer;
            }
        }

        return null;
    }
}
