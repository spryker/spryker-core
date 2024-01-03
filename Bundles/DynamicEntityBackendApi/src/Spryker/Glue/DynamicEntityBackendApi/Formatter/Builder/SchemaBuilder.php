<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

class SchemaBuilder implements SchemaBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_IN = 'in';

    /**
     * @var string
     */
    protected const KEY_REQUIRED = 'required';

    /**
     * @var string
     */
    protected const KEY_DESCRIPTION = 'description';

    /**
     * @var string
     */
    protected const KEY_SCHEMA = 'schema';

    /**
     * @var string
     */
    protected const KEY_TYPE = 'type';

    /**
     * @var string
     */
    protected const KEY_EXAMPLE = 'example';

    /**
     * @var string
     */
    protected const KEY_CONTENT = 'content';

    /**
     * @var string
     */
    protected const KEY_APPLICATION_JSON = 'application/json';

    /**
     * @var string
     */
    protected const KEY_SCHEMA_ITEMS = 'items';

    /**
     * @var string
     */
    protected const KEY_SCHEMA_PROPERTIES = 'properties';

    /**
     * @var string
     */
    protected const SCHEMA_TYPE_OBJECT = 'object';

    /**
     * @var string
     */
    protected const SCHEMA_TYPE_ARRAY = 'array';

    /**
     * @var string
     */
    protected const SCHEMA_ONE_OF = 'oneOf';

    /**
     * @var string
     */
    protected const APPLICATION_JSON = 'application/json';

    /**
     * @var string
     */
    protected const PROPERTY_NAME = 'data';

    /**
     * @param string $responseDescriptionValue
     * @param array<string, mixed> $schemaStructure
     * @param bool $isRequired
     *
     * @return array<string, mixed>
     */
    public function buildResponse(
        string $responseDescriptionValue,
        array $schemaStructure,
        bool $isRequired = false
    ): array {
        $responseBody = [
            static::KEY_DESCRIPTION => $responseDescriptionValue,
        ];

        if ($isRequired) {
            $responseBody[static::KEY_REQUIRED] = true;
        }

        $responseBody[static::KEY_CONTENT] = [
            static::KEY_APPLICATION_JSON => [
                static::KEY_SCHEMA => $schemaStructure,
            ],
        ];

        return $responseBody;
    }

    /**
     * @param array<string, mixed> $fieldsArray
     *
     * @return array<string, mixed>
     */
    public function buildRequestRootOneOfItem(array $fieldsArray): array
    {
        return [
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
        ];
    }

    /**
     * @param string $name
     * @param string $in
     * @param string $description
     * @param string $type
     * @param string|null $example
     *
     * @return array<string, mixed>
     */
    public function buildParameter(string $name, string $in, string $description, string $type, ?string $example = null): array
    {
        $parametersArray = [
            static::KEY_NAME => $name,
            static::KEY_IN => $in,
            static::KEY_REQUIRED => true,
            static::KEY_DESCRIPTION => $description,
            static::KEY_SCHEMA => [
                static::KEY_TYPE => $type,
            ],
        ];

        if ($example !== null && is_array($parametersArray[static::KEY_SCHEMA])) {
            $parametersArray[static::KEY_SCHEMA][static::KEY_EXAMPLE] = $example;
        }

        return $parametersArray;
    }

    /**
     * @param array<string, mixed> $fieldsArray
     *
     * @return array<string, mixed>
     */
    public function buildResponseRootOneOfItem(array $fieldsArray): array
    {
        return [
            static::KEY_TYPE => static::SCHEMA_TYPE_ARRAY,
            static::KEY_SCHEMA_ITEMS => [
                static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
                static::KEY_SCHEMA_PROPERTIES => $fieldsArray,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $fieldsArray
     *
     * @return array<string, mixed>
     */
    public function buildRootOneOfItem(array $fieldsArray): array
    {
        return [
            static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
            static::KEY_SCHEMA_PROPERTIES => $fieldsArray,
        ];
    }

    /**
     * @param string $descriptionValue
     * @param string $codeOrHttpCode
     * @param array<string, mixed> $schemaStructure
     *
     * @return array<string, array<string, mixed>>
     */
    public function buildResponseArray(
        string $descriptionValue,
        string $codeOrHttpCode,
        array $schemaStructure
    ): array {
        return [
            $codeOrHttpCode => $this->buildResponse($descriptionValue, $schemaStructure),
        ];
    }

    /**
     * @param array<string, mixed> $fieldsArray
     * @param bool $isCollection
     *
     * @return array<string, mixed>
     */
    public function generateSchemaStructure(array $fieldsArray, bool $isCollection): array
    {
        if ($isCollection) {
            return $this->buildRequestRootOneOfItem($fieldsArray);
        }

        return [
            static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
            static::KEY_SCHEMA_PROPERTIES => [
                static::PROPERTY_NAME => [
                    static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
                    static::KEY_SCHEMA_PROPERTIES => $fieldsArray,
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $oneOfFieldsArray
     * @param bool $isCollection
     *
     * @return array<string, mixed>
     */
    public function generateSchemaStructureOneOf(array $oneOfFieldsArray, bool $isCollection): array
    {
        $schemaStructure = [
            static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
            static::KEY_SCHEMA_PROPERTIES => [
                static::PROPERTY_NAME => [
                    static::SCHEMA_ONE_OF => $oneOfFieldsArray,
                ],
            ],
        ];

        if ($isCollection) {
            $schemaStructure = [
                static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
                static::KEY_SCHEMA_PROPERTIES => [
                    static::PROPERTY_NAME => [
                        static::KEY_TYPE => static::SCHEMA_TYPE_ARRAY,
                        static::KEY_SCHEMA_ITEMS => [
                            static::SCHEMA_ONE_OF => array_values($oneOfFieldsArray),
                        ],
                    ],
                ],
            ];
        }

        return $schemaStructure;
    }
}
