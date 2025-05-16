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
use Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToTaxAppClientInterface;
use Spryker\Glue\TaxAppRestApi\TaxAppRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class TaxIdValidator implements TaxIdValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_RESPONSE_DETAIL_INVALID_REQUEST_DATA = 'tax_app.vertex.invalid-request-data';

    /**
     * @var string
     */
    protected const GLOSSARY_SUFFIX_VERTEX = 'tax_app.vertex';

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToTaxAppClientInterface $taxAppClient
     * @param \Spryker\Glue\TaxAppRestApi\Dependency\TaxAppRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        protected RestResourceBuilderInterface $restResourceBuilder,
        protected TaxAppRestApiToTaxAppClientInterface $taxAppClient,
        protected TaxAppRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function validate(RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer, string $locale): RestResponseInterface
    {
        if (!$restTaxAppValidationAttributesTransfer->getTaxId() || !$restTaxAppValidationAttributesTransfer->getCountryCode()) {
            $messageByLocale = $this->getGlossaryMessage(TaxAppRestApiConfig::RESPONSE_DETAIL_MESSAGE_INVALID_REQUEST_DATA, $locale, static::GLOSSARY_KEY_RESPONSE_DETAIL_INVALID_REQUEST_DATA);

            return $this->restResourceBuilder->createRestResponse()->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setDetail($messageByLocale),
            );
        }
        $taxAppValidationRequestTransfer = (new TaxAppValidationRequestTransfer())->fromArray($restTaxAppValidationAttributesTransfer->toArray(), true);
        $taxAppValidationResponseTransfer = $this->taxAppClient->validateTaxId($taxAppValidationRequestTransfer);

        if ($taxAppValidationResponseTransfer->getIsValid()) {
            return $this->restResourceBuilder->createRestResponse()->setStatus(Response::HTTP_OK);
        }

        if ($taxAppValidationResponseTransfer->getMessage()) {
            $messageKey = $taxAppValidationResponseTransfer->getMessageKey() ? sprintf('%s.%s', static::GLOSSARY_SUFFIX_VERTEX, $taxAppValidationResponseTransfer->getMessageKey()) : null;

            $messageByLocale = $this->getGlossaryMessage($taxAppValidationResponseTransfer->getMessage(), $locale, $messageKey);

            return $this->restResourceBuilder->createRestResponse()->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setDetail($messageByLocale),
            )->setStatus(Response::HTTP_BAD_REQUEST);
        }

        return $this->restResourceBuilder->createRestResponse()->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param string $defaultMessage
     * @param string $locale
     * @param string|null $messageKey
     *
     * @return string|null
     */
    protected function getGlossaryMessage(string $defaultMessage, string $locale, ?string $messageKey): ?string
    {
        if (!$messageKey) {
            return $defaultMessage;
        }

        $message = $this->glossaryStorageClient->translate($messageKey, $locale);

        if ($message !== $messageKey) {
            return $message;
        }

        return $defaultMessage;
    }
}
