<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CheckoutRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckoutRequestValidator implements CheckoutRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface[]
     */
    protected $checkoutRequestAttributesValidatorPlugins;

    /**
     * @param \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface[] $checkoutRequestAttributesValidatorPlugins
     */
    public function __construct(
        array $checkoutRequestAttributesValidatorPlugins
    ) {
        $this->checkoutRequestAttributesValidatorPlugins = $checkoutRequestAttributesValidatorPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateCheckoutRequest(
        RestRequestInterface $restRequest,
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        $customerValidationError = $this->validateCustomer($restRequest);
        if ($customerValidationError !== null) {
            $restErrorCollectionTransfer->addRestError($customerValidationError);
        }

        foreach ($this->checkoutRequestAttributesValidatorPlugins as $checkoutRequestAttributesValidatorPlugin) {
            $pluginErrorCollectionTransfer = $checkoutRequestAttributesValidatorPlugin->validateAttributes($restCheckoutRequestAttributesTransfer);
            foreach ($pluginErrorCollectionTransfer->getRestErrors() as $restError) {
                $restErrorCollectionTransfer->addRestError($restError);
            }
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function validateCustomer(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        if ($restRequest->getUser() === null) {
            return (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_USER_IS_NOT_SPECIFIED)
                ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_USER_IS_NOT_SPECIFIED);
        }

        return null;
    }
}
