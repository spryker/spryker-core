<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

trait CustomersRestApiErrorsTrait
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addCustomerAlreadyExistsError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ALREADY_EXISTS)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(CustomersRestApiConfig::RESPONSE_MESSAGE_CUSTOMER_ALREADY_EXISTS);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param string $errorMessage
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addCustomerCantRegisterCustomerMessageError(RestResponseInterface $restResponse, string $errorMessage): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_CANT_REGISTER_CUSTOMER)
            ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setDetail($errorMessage);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addCustomerNotFoundError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addCustomerAddressesNotFoundError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ADDRESSES_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_ADDRESSES_NOT_FOUND);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addAddressNotFoundError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_ADDRESS_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_ADDRESS_NOT_FOUND);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addCustomerReferenceMissingError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_REFERENCE_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_REFERENCE_MISSING);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addPasswordsNotMatchError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_PASSWORDS_DONT_MATCH)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_PASSWORDS_DONT_MATCH);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param string $errorMessage
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addPasswordChangeError(RestResponseInterface $restResponse, string $errorMessage): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_PASSWORD_CHANGE_FAILED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail($errorMessage);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addPasswordNotValidError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_INVALID_PASSWORD)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_INVALID_PASSWORD);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addAddressNotSavedError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ADDRESS_FAILED_TO_SAVE)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_ADDRESS_FAILED_TO_SAVE);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addCustomerNotSavedError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_FAILED_TO_SAVE)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_FAILED_TO_SAVE);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addCustomerUnauthorizedError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_UNAUTHORIZED)
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_UNAUTHORIZED);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addAddressUuidMissingError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_ADDRESS_UUID_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_ADDRESS_UUID_MISSING);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addNotAcceptedTermsError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_NOT_ACCEPTED_TERMS)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_NOT_ACCEPTED_TERMS);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addNotValidGenderError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_NOT_VALID_GENDER)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_NOT_VALID_GENDER
                . ' Possible options are: ' . implode(', ', static::CUSTOMERS_GENDERS_ENUM));

        return $restResponse->addError($restErrorTransfer);
    }
}
