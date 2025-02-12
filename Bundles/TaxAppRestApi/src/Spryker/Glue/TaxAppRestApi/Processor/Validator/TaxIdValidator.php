<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxAppRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer;
use Generated\Shared\Transfer\TaxAppValidationRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToTaxAppClientInterface;
use Spryker\Glue\TaxAppRestApi\TaxAppRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class TaxIdValidator implements TaxIdValidatorInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToTaxAppClientInterface $taxAppClient
     */
    public function __construct(
        protected RestResourceBuilderInterface $restResourceBuilder,
        protected TaxAppRestApiToTaxAppClientInterface $taxAppClient
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function validate(RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer): RestResponseInterface
    {
        if (!$restTaxAppValidationAttributesTransfer->getTaxId() || !$restTaxAppValidationAttributesTransfer->getCountryCode()) {
            return $this->restResourceBuilder->createRestResponse()->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setDetail(TaxAppRestApiConfig::RESPONSE_DETAIL_MESSAGE_INVALID_REQUEST_DATA),
            );
        }
        $taxAppValidationRequestTransfer = (new TaxAppValidationRequestTransfer())->fromArray($restTaxAppValidationAttributesTransfer->toArray(), true);
        $taxAppValidationResponseTransfer = $this->taxAppClient->validateTaxId($taxAppValidationRequestTransfer);

        if ($taxAppValidationResponseTransfer->getIsValid()) {
            return $this->restResourceBuilder->createRestResponse()->setStatus(Response::HTTP_OK);
        }

        if ($taxAppValidationResponseTransfer->getMessage()) {
            return $this->restResourceBuilder->createRestResponse()->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setDetail($taxAppValidationResponseTransfer->getMessage()),
            )->setStatus(Response::HTTP_BAD_REQUEST);
        }

        return $this->restResourceBuilder->createRestResponse()->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
