<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class RestRequestValidator implements RestRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface[]
     */
    protected $restRequestValidatorPlugins = [];

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface[] $restRequestValidatorPlugins
     */
    public function __construct(array $restRequestValidatorPlugins)
    {
        $this->restRequestValidatorPlugins = $restRequestValidatorPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $httpRequest, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
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
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    protected function validateRequest(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $method = $restRequest->getMetadata()->getMethod();
        if (!\in_array($method, [Request::METHOD_POST, Request::METHOD_PATCH], true)) {
            return null;
        }

        $restResource = $restRequest->getResource();
        if (!$restResource->getAttributes()) {
            $restErrorMessageTransfer = new RestErrorMessageTransfer();
            $restErrorMessageTransfer->setDetail('Post data missing.');

            return (new RestErrorCollectionTransfer())->addRestErrors($restErrorMessageTransfer);
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    protected function executeRestRequestValidatorPlugins(
        Request $httpRequest,
        RestRequestInterface $restRequest
    ): ?RestErrorCollectionTransfer {

        foreach ($this->restRequestValidatorPlugins as $requestValidatorPlugin) {
            $restErrorCollectionTransfer = $requestValidatorPlugin->validate($httpRequest, $restRequest);
            if ($restErrorCollectionTransfer !== null) {
                return $restErrorCollectionTransfer;
            }
        }

        return null;
    }
}
