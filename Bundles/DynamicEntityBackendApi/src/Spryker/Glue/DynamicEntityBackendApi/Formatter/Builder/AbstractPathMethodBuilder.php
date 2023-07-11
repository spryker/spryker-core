<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Spryker\Glue\DynamicEntityBackendApi\Exception\MissingFieldDefinitionException;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPathMethodBuilder implements PathMethodBuilderInterface
{
    /**
     * @var string
     */
    protected const TAG_TPL = 'dynamic-entity-%s';

    /**
     * @var string
     */
    protected const DESCRIPTION_PARAMETER_ID = 'ID of entity %s';

    /**
     * @var string
     */
    protected const RESPONSE_ERROR_DEFAULT_MESSAGE = 'An error occurred.';

    /**
     * @var string
     */
    protected const RESPONSE_ERROR_NOT_FOUND_MESSAGE = 'Not Found.';

    /**
     * @var string
     */
    protected const RESPONSE_ERROR_UNAUTHORIZED_REQUEST_MESSAGE = 'Unauthorized request.';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT = 'Accept';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT_DESCRIPTION = 'The Accept request HTTP header indicates which content types, expressed as MIME types, the client is able to understand.';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE_DESCRIPTION = 'Content type of request body.';

    /**
     * @var string
     */
    protected const HEADER = 'header';

    /**
     * @var string
     */
    protected const APPLICATION_JSON = 'application/json';

    /**
     * @var string
     */
    protected const PATH = 'path';

    /**
     * @var string
     */
    protected const ID = 'id';

    /**
     * @var string
     */
    protected const PROPERTY_NAME = 'data';

    /**
     * @var string
     */
    protected const MISSING_FIELD_DEFINITIONS_EXCEPTION_MESSAGE = 'No fields defined for dynamic entity.';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    abstract public function buildPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig
     */
    protected DynamicEntityBackendApiConfig $config;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig $config
     */
    public function __construct(DynamicEntityBackendApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     * @param bool $skipIdentifier
     * @param bool $filterIsCreatable
     * @param bool $filterIsEditable
     *
     * @throws \Spryker\Glue\DynamicEntityBackendApi\Exception\MissingFieldDefinitionException
     *
     * @return array<mixed>
     */
    protected function prepareFieldsArray(
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer,
        bool $skipIdentifier = false,
        bool $filterIsCreatable = false,
        bool $filterIsEditable = false
    ): array {
        $result = [];

        if ($dynamicEntityDefinitionTransfer->getFieldDefinitions()->count() === 0) {
            throw new MissingFieldDefinitionException(static::MISSING_FIELD_DEFINITIONS_EXCEPTION_MESSAGE);
        }

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $field) {
            if ($skipIdentifier && $dynamicEntityDefinitionTransfer->getIdentifierOrFail() === $field->getFieldVisibleNameOrFail()) {
                continue;
            }

            if ($filterIsCreatable && !$field->getIsCreatable()) {
                continue;
            }

            if ($filterIsEditable && !$field->getIsEditable()) {
                continue;
            }

            $result[$field->getFieldVisibleNameOrFail()] = $this->buildFieldType($field);
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildFieldType(DynamicEntityFieldDefinitionTransfer $dynamicEntityFieldDefinitionTransfer): array
    {
        $filedType = [static::KEY_TYPE => $dynamicEntityFieldDefinitionTransfer->getTypeOrFail()];

        $dynamicEntityFieldValidationTransfer = $dynamicEntityFieldDefinitionTransfer->getValidation();

        if ($dynamicEntityFieldValidationTransfer === null) {
            return $filedType;
        }

        if ($dynamicEntityFieldValidationTransfer->getMin() !== null) {
            $filedType[static::KEY_TYPE_MIN] = $dynamicEntityFieldValidationTransfer->getMin();
        }

        if ($dynamicEntityFieldValidationTransfer->getMax() !== null) {
            $filedType[static::KEY_TYPE_MAX] = $dynamicEntityFieldValidationTransfer->getMax();
        }

        if ($dynamicEntityFieldValidationTransfer->getMinLength() !== null) {
            $filedType[static::KEY_TYPE_MIN_LENGTH] = $dynamicEntityFieldValidationTransfer->getMinLength();
        }

        if ($dynamicEntityFieldValidationTransfer->getMaxLength() !== null) {
            $filedType[static::KEY_TYPE_MAX_LENGTH] = $dynamicEntityFieldValidationTransfer->getMaxLength();
        }

        if ($dynamicEntityFieldValidationTransfer->getPrecision() !== null) {
            $filedType[static::KEY_TYPE_MIN_LENGTH] = 1;
            $filedType[static::KEY_TYPE_MAX_LENGTH] = $dynamicEntityFieldValidationTransfer->getPrecision() + 1;
        }

        return $filedType;
    }

    /**
     * @param string $placeholder
     * @param string $resourceName
     *
     * @return string
     */
    protected function formatPath(string $placeholder, string $resourceName): string
    {
        return str_replace('//', '/', sprintf($placeholder, $this->config->getRoutePrefix(), $resourceName));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function getTag(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        return sprintf(static::TAG_TPL, $dynamicEntityConfigurationTransfer->getTableAliasOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildIdParameter(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return [
            static::KEY_NAME => static::ID,
            static::KEY_IN => static::PATH,
            static::KEY_REQUIRED => true,
            static::KEY_DESCRIPTION => sprintf(static::DESCRIPTION_PARAMETER_ID, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
            static::KEY_SCHEMA => [
                static::KEY_TYPE => static::SCHEMA_TYPE_INTEGER,
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildHeaderContentTypeParameter(): array
    {
        return [
            static::KEY_NAME => static::HEADER_CONTENT_TYPE,
            static::KEY_IN => static::HEADER,
            static::KEY_DESCRIPTION => static::HEADER_CONTENT_TYPE_DESCRIPTION,
            static::KEY_REQUIRED => true,
            static::KEY_SCHEMA => [
                static::KEY_TYPE => static::SCHEMA_TYPE_STRING,
                static::KEY_EXAMPLE => static::APPLICATION_JSON,
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildHeaderAcceptParameter(): array
    {
        return [
            static::KEY_NAME => static::HEADER_ACCEPT,
            static::KEY_IN => static::HEADER,
            static::KEY_DESCRIPTION => static::HEADER_ACCEPT_DESCRIPTION,
            static::KEY_REQUIRED => true,
            static::KEY_SCHEMA => [
                static::KEY_TYPE => static::SCHEMA_TYPE_STRING,
                static::KEY_EXAMPLE => static::APPLICATION_JSON,
            ],
        ];
    }

    /**
     * @param string $description
     *
     * @return array<mixed>
     */
    protected function buildResponseError(string $description): array
    {
        return [
            static::KEY_DESCRIPTION => $description,
            static::KEY_CONTENT => [
                static::KEY_APPLICATION_JSON => [
                    static::KEY_SCHEMA => [
                        static::KEY_SCHEMA_REF => static::SCHEMA_REF_COMPONENT_REST_ERROR,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildResponseDefault(): array
    {
        return [
            static::KEY_RESPONSE_DEFAULT => $this->buildResponseError(static::RESPONSE_ERROR_DEFAULT_MESSAGE),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildResponseNotFound(): array
    {
        return [
            (string)Response::HTTP_NOT_FOUND => $this->buildResponseError(static::RESPONSE_ERROR_NOT_FOUND_MESSAGE),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function buildResponseUnauthorizedRequest(): array
    {
        return [
            (string)Response::HTTP_FORBIDDEN => $this->buildResponseError(static::RESPONSE_ERROR_UNAUTHORIZED_REQUEST_MESSAGE),
        ];
    }

    /**
     * @param string $responseDescriptionValue
     * @param array<mixed> $fieldsArray
     * @param string $code
     *
     * @return array<string, array<mixed>>
     */
    protected function buildResponseSuccess(
        string $responseDescriptionValue,
        array $fieldsArray,
        string $code
    ): array {
        return [
            $code => [
                static::KEY_DESCRIPTION => $responseDescriptionValue,
                static::KEY_CONTENT => [
                    static::KEY_APPLICATION_JSON => [
                        static::KEY_SCHEMA => [
                            static::KEY_TYPE => static::SCHEMA_TYPE_ARRAY,
                            static::KEY_SCHEMA_ITEMS => [
                                static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
                                static::KEY_SCHEMA_PROPERTIES => $fieldsArray,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $descriptionValue
     * @param array<mixed> $fieldsArray
     *
     * @return array<string, array<string, mixed>>
     */
    protected function buildRequestBody(string $descriptionValue, array $fieldsArray): array
    {
        return [
            static::KEY_REQUEST_BODY => [
                static::KEY_DESCRIPTION => $descriptionValue,
                static::KEY_REQUIRED => true,
                static::KEY_CONTENT => [
                    static::KEY_APPLICATION_JSON => [
                        static::KEY_SCHEMA => [
                            static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
                            static::KEY_SCHEMA_PROPERTIES => [
                                static::PROPERTY_NAME => [
                                    static::KEY_TYPE => static::SCHEMA_TYPE_ARRAY,
                                    static::KEY_SCHEMA_ITEMS => [
                                        static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
                                        static::KEY_SCHEMA_PROPERTIES => $fieldsArray,
                                    ],
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $operationId
     * @param string $summary
     *
     * @return array<string, mixed>
     */
    protected function expandPathData(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $operationId,
        string $summary
    ): array {
        return [
            static::KEY_TAGS => [$this->getTag($dynamicEntityConfigurationTransfer)],
            static::KEY_OPERATION_ID => sprintf(
                $operationId,
                $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
            ), static::KEY_SUMMARY => sprintf($summary, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildKeyParameters(): array
    {
        return [
            static::KEY_PARAMETERS => [
                $this->buildHeaderContentTypeParameter(),
                $this->buildHeaderAcceptParameter(),
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildKeyParametersWithIdParameter(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return [
            static::KEY_PARAMETERS => [
                $this->buildIdParameter($dynamicEntityConfigurationTransfer),
                $this->buildHeaderContentTypeParameter(),
                $this->buildHeaderAcceptParameter(),
            ],
        ];
    }

    /**
     * @param array<mixed> $responses
     *
     * @return array<string, mixed>
     */
    protected function buildResponses(array $responses): array
    {
        return [
            static::KEY_RESPONSES => $responses,
        ];
    }
}
