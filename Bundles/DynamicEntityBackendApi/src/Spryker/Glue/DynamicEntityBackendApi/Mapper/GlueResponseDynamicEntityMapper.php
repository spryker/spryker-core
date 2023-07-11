<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Mapper;

use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToLocaleClientInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class GlueResponseDynamicEntityMapper
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_PERSISTENCE_FAILED = 'dynamic_entity.validation.persistence_failed';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED = 'dynamic_entity.validation.modification_of_immutable_field_prohibited';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_INVALID_FIELD_TYPE = 'dynamic_entity.validation.invalid_field_type';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_INVALID_FIELD_VALUE = 'dynamic_entity.validation.invalid_field_value';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_REQUIRED_FIELD_IS_MISSING = 'dynamic_entity.validation.required_field_is_missing';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ENTITY_NOT_FOUND_OR_IDENTIFIER_IS_NOT_CREATABLE = 'dynamic_entity.validation.entity_not_found_or_identifier_is_not_creatable';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_ENTITY_NOT_PERSISTED_DUPLICATE_ENTRY = 'dynamic_entity.validation.persistence_failed_duplicate_entry';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_MISSING_IDENTIFIER = 'dynamic_entity.validation.missing_identifier';

    /**
     * @var int
     */
    protected const RESPONSE_CODE_PARAMETERS_ARE_INVALID = 501;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_DATA_PERSISTENCE_FAILED = 502;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_MODEL_DOES_NOT_EXIST = 503;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_ENTITY_DOES_NOT_EXIST = 504;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED = 505;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_CONFIG_IS_NOT_FOUND = 506;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_INVALID_FIELD_TYPE = 507;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_INVALID_FIELD_VALUE = 508;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_REQUIRED_FIELD_IS_MISSING = 509;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_IDENTIFIER_IS_NOT_CREATABLE = 510;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_ENTITY_NOT_PERSISTED_DUPLICATE_ENTRY = 511;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_MISSING_IDENTIFIER = 512;

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const CONTENT_TYPE_APP_JSON = 'application/json';

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface
     */
    protected DynamicEntityBackendApiToUtilEncodingServiceInterface $serviceUtilEncoding;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToGlossaryStorageClientInterface
     */
    protected DynamicEntityBackendApiToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToLocaleClientInterface
     */
    protected DynamicEntityBackendApiToLocaleClientInterface $localeClient;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface $serviceUtilEncoding
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToLocaleClientInterface $localeClient
     */
    public function __construct(
        DynamicEntityBackendApiToUtilEncodingServiceInterface $serviceUtilEncoding,
        DynamicEntityBackendApiToGlossaryStorageClientInterface $glossaryStorageClient,
        DynamicEntityBackendApiToLocaleClientInterface $localeClient
    ) {
        $this->serviceUtilEncoding = $serviceUtilEncoding;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapDynamicEntityCollectionTransferToGlueResponseTransfer(
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
    ): GlueResponseTransfer {
        $glueResponseTransfer = $this->createGlueResponseTransfer();

        if ($dynamicEntityCollectionTransfer->getErrors()->count()) {
            foreach ($dynamicEntityCollectionTransfer->getErrors() as $errorTransfer) {
                $glueResponseTransfer = $this->mapErrorToResponseTransfer(
                    $errorTransfer->getMessageOrFail(),
                    $glueResponseTransfer,
                    $errorTransfer->getParameters(),
                );
            }

            return $glueResponseTransfer;
        }

        $fieldsCollection = [];

        foreach ($dynamicEntityCollectionTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $fieldsCollection[] = $dynamicEntityTransfer->getFields();
        }

        $glueResponseTransfer->setContent($this->serviceUtilEncoding->encodeJson($fieldsCollection));
        $glueResponseTransfer->setPagination($dynamicEntityCollectionTransfer->getPagination());

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapDynamicEntityCollectionResponseTransferToGlueResponseTransfer(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): GlueResponseTransfer {
        $glueResponseTransfer = $this->createGlueResponseTransfer();

        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count() !== 0) {
            foreach ($dynamicEntityCollectionResponseTransfer->getErrors() as $errorTransfer) {
                $glueResponseTransfer = $this->mapErrorToResponseTransfer(
                    $errorTransfer->getMessageOrFail(),
                    $glueResponseTransfer,
                    $errorTransfer->getParameters(),
                );
            }

            return $glueResponseTransfer;
        }

        $fieldsCollection = [];

        foreach ($dynamicEntityCollectionResponseTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $fieldsCollection[] = $dynamicEntityTransfer->getFields();
        }

        $glueResponseTransfer->setContent($this->serviceUtilEncoding->encodeJson($fieldsCollection));

        return $glueResponseTransfer;
    }

    /**
     * @param string $message
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param array<string, string> $parameters
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapErrorToResponseTransfer(
        string $message,
        GlueResponseTransfer $glueResponseTransfer,
        array $parameters = []
    ): GlueResponseTransfer {
        $errorMessage = $this->glossaryStorageClient->translate(
            $message,
            $this->localeClient->getCurrentLocale(),
            $parameters,
        );

        $errorDataIndexedByGlossaryKey = $this->getErrorDataIndexedByGlossaryKey()[$message];

        $glueResponseTransfer->setHttpStatus($errorDataIndexedByGlossaryKey[GlueErrorTransfer::STATUS]);
        $glueResponseTransfer->addError(
            (new GlueErrorTransfer())
                ->setCode((string)$errorDataIndexedByGlossaryKey[GlueErrorTransfer::CODE])
                ->setStatus($errorDataIndexedByGlossaryKey[GlueErrorTransfer::STATUS])
                ->setMessage($errorMessage),
        );

        return $glueResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function createGlueResponseTransfer(): GlueResponseTransfer
    {
        $glueResponseTransfer = new GlueResponseTransfer();
        $glueResponseTransfer->setMeta(array_merge($glueResponseTransfer->getMeta(), $this->getResponseHeaders()));

        return $glueResponseTransfer;
    }

    /**
     * @return array<string, string>
     */
    public function getResponseHeaders(): array
    {
        return [
            static::HEADER_CONTENT_TYPE => static::CONTENT_TYPE_APP_JSON,
        ];
    }

    /**
     * Specification:
     * - Returns a map of glossary keys for error data.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getErrorDataIndexedByGlossaryKey(): array
    {
        return [
            DynamicEntityBackendApiConfig::GLOSSARY_KEY_ERROR_INVALID_DATA_FORMAT => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PARAMETERS_ARE_INVALID,
            ],
            static::GLOSSARY_KEY_ERROR_PERSISTENCE_FAILED => [
                GlueErrorTransfer::STATUS => Response::HTTP_INTERNAL_SERVER_ERROR,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_DATA_PERSISTENCE_FAILED,
            ],
            DynamicEntityBackendApiConfig::GLOSSARY_KEY_ERROR_ENTITY_DOES_NOT_EXIST => [
                GlueErrorTransfer::STATUS => Response::HTTP_NOT_FOUND,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_ENTITY_DOES_NOT_EXIST,
            ],
            static::GLOSSARY_KEY_ERROR_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED,
            ],
            static::GLOSSARY_KEY_ERROR_MISSING_IDENTIFIER => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_MISSING_IDENTIFIER,
            ],
            static::GLOSSARY_KEY_ERROR_INVALID_FIELD_TYPE => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_INVALID_FIELD_TYPE,
            ],
            static::GLOSSARY_KEY_ERROR_INVALID_FIELD_VALUE => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_INVALID_FIELD_VALUE,
            ],
            static::GLOSSARY_KEY_REQUIRED_FIELD_IS_MISSING => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_REQUIRED_FIELD_IS_MISSING,
            ],
            static::GLOSSARY_KEY_ERROR_ENTITY_NOT_FOUND_OR_IDENTIFIER_IS_NOT_CREATABLE => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_IDENTIFIER_IS_NOT_CREATABLE,
            ],
            static::GLOSSARY_KEY_ERROR_ENTITY_NOT_PERSISTED_DUPLICATE_ENTRY => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_ENTITY_NOT_PERSISTED_DUPLICATE_ENTRY,
            ],

        ];
    }
}
