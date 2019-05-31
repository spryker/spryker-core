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
     * @var \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface[] $checkoutRequestAttributesValidatorPlugins
     * @param \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig $config
     */
    public function __construct(
        array $checkoutRequestAttributesValidatorPlugins,
        CheckoutRestApiConfig $config
    ) {
        $this->checkoutRequestAttributesValidatorPlugins = $checkoutRequestAttributesValidatorPlugins;
        $this->config = $config;
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
        $restErrorCollectionTransfer = $this->validateCustomer($restRequest, $restErrorCollectionTransfer);
        $restErrorCollectionTransfer = $this->validatePayments($restCheckoutRequestAttributesTransfer, $restErrorCollectionTransfer);

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
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    protected function validateCustomer(RestRequestInterface $restRequest, RestErrorCollectionTransfer $restErrorCollectionTransfer): ?RestErrorCollectionTransfer
    {
        if ($restRequest->getUser() === null) {
            $resErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_USER_IS_NOT_SPECIFIED)
                ->setDetail(CheckoutRestApiConfig::RESPONSE_DETAILS_USER_IS_NOT_SPECIFIED);

            $restErrorCollectionTransfer->addRestError($resErrorMessageTransfer);
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\RestErrorCollectionTransfer $restErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function validatePayments(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer, RestErrorCollectionTransfer $restErrorCollectionTransfer): RestErrorCollectionTransfer
    {
        $paymentProviderMethodToStateMachineMapping = $this->config->getPaymentProviderMethodToStateMachineMapping();
        foreach ($restCheckoutRequestAttributesTransfer->getPayments() as $restPaymentTransfer) {
            if (!isset($paymentProviderMethodToStateMachineMapping[$restPaymentTransfer->getPaymentProviderName()][$restPaymentTransfer->getPaymentMethodName()])) {
                $resErrorMessageTransfer = (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(CheckoutRestApiConfig::RESPONSE_CODE_INVALID_PAYMENT)
                    ->setDetail(sprintf(
                        CheckoutRestApiConfig::RESPONSE_DETAILS_INVALID_PAYMENT,
                        $restPaymentTransfer->getPaymentMethodName(),
                        $restPaymentTransfer->getPaymentProviderName()
                    ));

                $restErrorCollectionTransfer->addRestError($resErrorMessageTransfer);
            }
        }

        return $restErrorCollectionTransfer;
    }
}
