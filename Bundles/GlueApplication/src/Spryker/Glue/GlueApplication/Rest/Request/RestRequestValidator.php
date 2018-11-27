<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class RestRequestValidator implements RestRequestValidatorInterface
{
    protected const EXCEPTION_MESSAGE_POST_DATA_IS_INVALID = 'Post data is invalid.';

    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface[]
     */
    protected $validateRestRequestPlugins = [];

    /**
     * @var array|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface[]
     */
    protected $restRequestValidatorPlugins = [];

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface[] $validateRestRequestPlugins
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface[] $restRequestValidatorPlugins
     */
    public function __construct(array $validateRestRequestPlugins, array $restRequestValidatorPlugins)
    {
        $this->validateRestRequestPlugins = $validateRestRequestPlugins;
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
        $restErrorCollectionTransfer = $this->validateRequest($restRequest);
        if (!$restErrorCollectionTransfer) {
            $restErrorCollectionTransfer = $this->executeRestRequestValidatorPlugins($httpRequest, $restRequest);
        }
        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    protected function validateRequest(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $method = $restRequest->getMetadata()->getMethod();
        if (!in_array($method, [Request::METHOD_POST, Request::METHOD_PATCH], true)) {
            return null;
        }

        $restResource = $restRequest->getResource();
        if (!$restResource->getAttributes()) {
            $restErrorMessageTransfer = new RestErrorMessageTransfer();
            $restErrorMessageTransfer->setDetail(static::EXCEPTION_MESSAGE_POST_DATA_IS_INVALID);

            return (new RestErrorCollectionTransfer())->addRestError($restErrorMessageTransfer);
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

        foreach ($this->restRequestValidatorPlugins as $restRequestValidatorPlugin) {
            $restErrorCollectionTransfer = $restRequestValidatorPlugin->validate($httpRequest, $restRequest);
            if ($restErrorCollectionTransfer !== null) {
                return $restErrorCollectionTransfer;
            }
        }

        foreach ($this->validateRestRequestPlugins as $validateRestRequestPlugins) {
            $restErrorMessageTransfer = $validateRestRequestPlugins->validate($httpRequest, $restRequest);
            if ($restErrorMessageTransfer !== null) {
                return (new RestErrorCollectionTransfer())->addRestError($restErrorMessageTransfer);
            }
        }

        return null;
    }
}
