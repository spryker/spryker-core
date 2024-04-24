<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToLocaleFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Spryker\Shared\DynamicEntityBackendApi\Transfer\DynamicEntityAttributes;
use Symfony\Component\HttpFoundation\Response;

class GlueResponseDynamicEntityMapper
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURATION_NOT_FOUND = 'dynamic_entity.validation.configuration_not_found';

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
    protected const GLOSSARY_KEY_INVALID_URL = 'dynamic_entity.validation.invalid_url';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_RELATION_NOT_FOUND = 'dynamic_entity.validation.relation_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PROVIDED_FIELD_IS_INVALID = 'dynamic_entity.validation.provided_field_is_invalid';

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
     * @var string
     */
    protected const GLOSSARY_KEY_NOT_EDITABLE_RELATION = 'dynamic_entity.validation.relation_is_not_editable';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_FILTER_FIELD_NOT_FOUND = 'dynamic_entity.validation.filter_field_not_found';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_DATA = 'data';

    /**
     * @var int
     */
    protected const RESPONSE_CODE_PARAMETERS_ARE_INVALID = 1301;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_DATA_PERSISTENCE_FAILED = 1302;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_ENTITY_DOES_NOT_EXIST = 1303;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_MODIFICATION_OF_IMMUTABLE_FIELD_PROHIBITED = 1304;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_INVALID_FIELD_TYPE = 1305;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_INVALID_FIELD_VALUE = 1306;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_REQUIRED_FIELD_IS_MISSING = 1307;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_IDENTIFIER_IS_NOT_CREATABLE = 1308;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_ENTITY_NOT_PERSISTED_DUPLICATE_ENTRY = 1309;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_MISSING_IDENTIFIER = 1310;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_PROVIDED_FIELD_IS_INVALID = 1311;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_CONFIGURATION_NOT_FOUND = 1312;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_RELATION_NOT_FOUND = 1313;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_RELATION_IS_NOT_EDITABLE = 1314;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_FILTER_FIELD_NOT_FOUND = 1315;

    /**
     * @var int
     */
    protected const RESPONSE_CODE_INVALID_URL = 1316;

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
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToLocaleFacadeInterface
     */
    protected DynamicEntityBackendApiToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface $serviceUtilEncoding
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        DynamicEntityBackendApiToUtilEncodingServiceInterface $serviceUtilEncoding,
        DynamicEntityBackendApiToGlossaryStorageClientInterface $glossaryStorageClient,
        DynamicEntityBackendApiToLocaleFacadeInterface $localeFacade
    ) {
        $this->serviceUtilEncoding = $serviceUtilEncoding;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     * @param string $tableAlias
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapDynamicEntityCollectionTransferToGlueResponseTransfer(
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer,
        string $tableAlias,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        $glueResponseTransfer = $this->createGlueResponseTransfer();
        $glueResponseTransfer->setPagination($dynamicEntityCollectionTransfer->getPagination());

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

        if ($this->isRequestedApplicationJson($glueRequestTransfer)) {
            $fieldsCollection = $this->mapDynamicEntitiesToFieldsCollection($dynamicEntityCollectionTransfer->getDynamicEntities(), $glueRequestTransfer);
            $glueResponseTransfer->setContent($this->buildResponseContent($fieldsCollection));

            return $glueResponseTransfer;
        }

        foreach ($dynamicEntityCollectionTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $glueResponseTransfer->addResource(
                $this->createGlueResourceTransfer($dynamicEntityTransfer, $tableAlias),
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param array<mixed, mixed> $fieldsCollection
     *
     * @return string|null
     */
    protected function buildResponseContent(array $fieldsCollection): ?string
    {
        return $this->serviceUtilEncoding->encodeJson(
            [
                static::RESPONSE_KEY_DATA => $fieldsCollection,
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer|null $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapDynamicEntityCollectionResponseTransferToGlueResponseTransfer(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        ?GlueRequestTransfer $glueRequestTransfer = null
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

        foreach ($dynamicEntityCollectionResponseTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $glueResponseTransfer->addResource(
                $this->createGlueResourceTransfer($dynamicEntityTransfer, $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail()),
            );
        }

        $fieldsCollection = $this->mapDynamicEntitiesToFieldsCollection($dynamicEntityCollectionResponseTransfer->getDynamicEntities(), $glueRequestTransfer);
        $glueResponseTransfer->setContent($this->buildResponseContent($fieldsCollection));

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
            $this->localeFacade->getCurrentLocaleName(),
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
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param string $tableAlias
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createGlueResourceTransfer(DynamicEntityTransfer $dynamicEntityTransfer, string $tableAlias): GlueResourceTransfer
    {
        $dynamicEntityAttributesTransfer = new DynamicEntityAttributes();

        $dynamicEntityAttributesTransfer->fromArray($this->getFieldsCollection(new ArrayObject([$dynamicEntityTransfer]))[0], true);

        return (new GlueResourceTransfer())
            ->setType($tableAlias)
            ->setAttributes($dynamicEntityAttributesTransfer)
            ->setId($dynamicEntityTransfer->getIdentifierOrFail());
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
     * @return array<string, array<string, mixed>>
     */
    protected function getErrorDataIndexedByGlossaryKey(): array
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
            static::GLOSSARY_KEY_INVALID_URL => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_INVALID_URL,
            ],
            static::GLOSSARY_KEY_PROVIDED_FIELD_IS_INVALID => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_PROVIDED_FIELD_IS_INVALID,
            ],
            static::GLOSSARY_KEY_ERROR_ENTITY_NOT_FOUND_OR_IDENTIFIER_IS_NOT_CREATABLE => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_IDENTIFIER_IS_NOT_CREATABLE,
            ],
            static::GLOSSARY_KEY_ERROR_ENTITY_NOT_PERSISTED_DUPLICATE_ENTRY => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_ENTITY_NOT_PERSISTED_DUPLICATE_ENTRY,
            ],
            static::GLOSSARY_KEY_CONFIGURATION_NOT_FOUND => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_CONFIGURATION_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_RELATION_NOT_FOUND => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_RELATION_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_NOT_EDITABLE_RELATION => [
                GlueErrorTransfer::STATUS => Response::HTTP_UNPROCESSABLE_ENTITY,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_RELATION_IS_NOT_EDITABLE,
            ],
            static::GLOSSARY_KEY_ERROR_FILTER_FIELD_NOT_FOUND => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_FILTER_FIELD_NOT_FOUND,
            ],
        ];
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityTransfer> $dynamicEntities
     * @param \Generated\Shared\Transfer\GlueRequestTransfer|null $glueRequestTransfer
     *
     * @return array<mixed>
     */
    protected function mapDynamicEntitiesToFieldsCollection(
        ArrayObject $dynamicEntities,
        ?GlueRequestTransfer $glueRequestTransfer
    ): array {
        if ($glueRequestTransfer !== null && $glueRequestTransfer->getResourceOrFail()->getId() !== null) {
            return $this->getFieldsCollection($dynamicEntities)[0];
        }

        return $this->getFieldsCollection($dynamicEntities);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityTransfer> $dynamicEntities
     *
     * @return array<mixed>
     */
    protected function getFieldsCollection(ArrayObject $dynamicEntities): array
    {
        $fieldsCollection = [];
        foreach ($dynamicEntities as $dynamicEntityTransfer) {
            $fields = $dynamicEntityTransfer->getFields();

            if ($dynamicEntityTransfer->getChildRelations()->count() !== 0) {
                $fields = $this->mapChildFieldsToFields($dynamicEntityTransfer, $fields);
            }

            $fieldsCollection[] = $fields;
        }

        return $fieldsCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<mixed> $fields
     *
     * @return array<string, mixed>
     */
    protected function mapChildFieldsToFields(DynamicEntityTransfer $dynamicEntityTransfer, array $fields): array
    {
        foreach ($dynamicEntityTransfer->getChildRelations() as $childRelation) {
            $childFields = $this->getFieldsCollection($childRelation->getDynamicEntities());
            $fields[$childRelation->getNameOrFail()] = $childFields;
        }

        return $fields;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function isRequestedApplicationJson(GlueRequestTransfer $glueRequestTransfer): bool
    {
        return strpos($glueRequestTransfer->getRequestedFormatOrFail(), static::CONTENT_TYPE_APP_JSON) !== false;
    }
}
