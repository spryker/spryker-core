<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestSspInquiriesAttributesTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspInquiriesMapperInterface;
use SprykerFeature\Glue\SelfServicePortal\SelfServicePortalConfig;
use Symfony\Component\HttpFoundation\Response;

class SspInquiriesResponseBuilder implements SspInquiriesResponseBuilderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_INQUIRY_NOT_FOUND = 'self_service_portal.inquiry.error.not-found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MESSAGE_INQUIRY_CREATION_ACCESS_DENIED = 'self_service_portal.inquiry.access.denied';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_INQUIRY_SUBJECT = 'self_service_portal.inquiry.validation.subject.not_set';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_INQUIRY_SUBJECT_TOO_LONG = 'self_service_portal.inquiry.validation.subject.too_long';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_INQUIRY_DESCRIPTION_TOO_LONG = 'self_service_portal.inquiry.validation.description.too_long';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_INQUIRY_TYPE = 'self_service_portal.inquiry.validation.type.invalid';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_INQUIRY_TYPE_NOT_SET = 'self_service_portal.inquiry.validation.type.not_set';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_INQUIRY_COMPANY_USER_NOT_SET = 'self_service_portal.inquiry.validation.company_user.not_set';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_UNKNOWN_ERROR = 'self_service_portal.inquiry.validation.unknown_error';

    /**
     * @var array<string, int>
     */
    protected const GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING = [
        self::GLOSSARY_KEY_ERROR_INQUIRY_NOT_FOUND => Response::HTTP_NOT_FOUND,
        self::GLOSSARY_KEY_MESSAGE_INQUIRY_CREATION_ACCESS_DENIED => Response::HTTP_FORBIDDEN,
        self::GLOSSARY_KEY_INVALID_INQUIRY_SUBJECT => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::GLOSSARY_KEY_INVALID_INQUIRY_DESCRIPTION_TOO_LONG => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::GLOSSARY_KEY_INVALID_INQUIRY_SUBJECT_TOO_LONG => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::GLOSSARY_KEY_INVALID_INQUIRY_TYPE => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::GLOSSARY_KEY_INVALID_INQUIRY_TYPE_NOT_SET => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::GLOSSARY_KEY_INVALID_INQUIRY_COMPANY_USER_NOT_SET => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::GLOSSARY_KEY_UNKNOWN_ERROR => Response::HTTP_BAD_REQUEST,
    ];

    public function __construct(
        protected RestResourceBuilderInterface $restResourceBuilder,
        protected GlossaryStorageClientInterface $glossaryStorageClient,
        protected SspInquiriesMapperInterface $sspInquiriesMapper
    ) {
    }

    public function createSspInquiryCollectionRestResponse(
        SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $restSspInquiriesAttributesTransfer = $this->sspInquiriesMapper->mapSspInquiryTransferToRestSspInquiriesAttributesTransfer($sspInquiryTransfer);
            $inquiryResource = $this->createSspInquiryRestResource($restSspInquiriesAttributesTransfer, $sspInquiryTransfer->getReferenceOrFail());
            $restResponse->addResource($inquiryResource);
        }

        return $restResponse;
    }

    public function createSspInquiryRestResponseFromSspInquiryCollectionTransfer(
        SspInquiryCollectionTransfer $sspInquiryCollectionTransfer,
        string $localeName
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $inquiryTransfers = $sspInquiryCollectionTransfer->getSspInquiries();

        if ($inquiryTransfers->count() === 0) {
            $restResponse = $this->restResourceBuilder->createRestResponse();
            $errorTransfer = (new RestErrorMessageTransfer())
                ->setStatus(static::GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING[static::GLOSSARY_KEY_ERROR_INQUIRY_NOT_FOUND])
                ->setDetail($this->glossaryStorageClient->translate(static::GLOSSARY_KEY_ERROR_INQUIRY_NOT_FOUND, $localeName));
            $restResponse->addError($errorTransfer);

            return $restResponse;
        }

        $sspInquiryTransfer = $inquiryTransfers->getIterator()->current();

        $restSspInquiriesAttributesTransfer = $this->sspInquiriesMapper->mapSspInquiryTransferToRestSspInquiriesAttributesTransfer($sspInquiryTransfer);
        $inquiryResource = $this->createSspInquiryRestResource($restSspInquiriesAttributesTransfer, $sspInquiryTransfer->getReferenceOrFail());
        $restResponse->addResource($inquiryResource);

        return $restResponse;
    }

    public function createInquiryRestResponseFromSspInquiryCollectionResponseTransfer(
        SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer,
        string $localeName
    ): RestResponseInterface {
        $errors = $sspInquiryCollectionResponseTransfer->getErrors();

        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $errorTransfer) {
            $errorMessageStatus = static::GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING[$errorTransfer->getMessage()] ?? static::GLOSSARY_KEY_TO_ERROR_STATUS_MAPPING[static::GLOSSARY_KEY_UNKNOWN_ERROR];

            $errorTransfer = (new RestErrorMessageTransfer())
                ->setStatus($errorMessageStatus)
                ->setDetail($this->glossaryStorageClient->translate($errorTransfer->getMessageOrFail(), $localeName));

            $restResponse->addError($errorTransfer);
        }

        $sspInquiryTransfers = $sspInquiryCollectionResponseTransfer->getSspInquiries();

        foreach ($sspInquiryTransfers as $sspInquiryTransfer) {
            $restSspInquiriesAttributesTransfer = $this->sspInquiriesMapper->mapSspInquiryTransferToRestSspInquiriesAttributesTransfer($sspInquiryTransfer);
            $inquiryResource = $this->createSspInquiryRestResource($restSspInquiriesAttributesTransfer, $sspInquiryTransfer->getReferenceOrFail());
            $restResponse->addResource($inquiryResource);
        }

        return $restResponse;
    }

    protected function createSspInquiryRestResource(
        RestSspInquiriesAttributesTransfer $restSspInquiriesAttributesTransfer,
        string $reference
    ): RestResourceInterface {
        return $this->restResourceBuilder->createRestResource(
            SelfServicePortalConfig::RESOURCE_SSP_INQUIRIES,
            $reference,
            $restSspInquiriesAttributesTransfer,
        );
    }
}
