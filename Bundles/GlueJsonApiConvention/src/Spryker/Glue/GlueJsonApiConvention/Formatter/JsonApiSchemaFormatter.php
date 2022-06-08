<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\GlueJsonApiConvention\Dependency\External\GlueJsonApiConventionToInflectorInterface;
use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Symfony\Component\HttpFoundation\Response;

class JsonApiSchemaFormatter implements SchemaFormatterInterface
{
    /**
     * @var string
     */
    protected const PATTERN_PATH_ID = '{%sId}';

    /**
     * @var string
     */
    protected const KEY_REF = '$ref';

    /**
     * @var string
     */
    protected const KEY_SCHEMA = 'schema';

    /**
     * @var string
     */
    protected const PART_REQUEST = 'RestRequest';

    /**
     * @var string
     */
    protected const PART_REST = 'Rest';

    /**
     * @var string
     */
    protected const PART_RESPONSE = 'Response';

    /**
     * @var string
     */
    protected const PART_COLLECTION = 'Collection';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'JsonApiErrorMessage';

    /**
     * @var string
     */
    protected const DEFAULT_RESPONSE = 'default';

    /**
     * @var string
     */
    protected const SCHEMA_REF = '#/components/schemas/';

    /**
     * @var \Spryker\Glue\GlueJsonApiConvention\Dependency\External\GlueJsonApiConventionToInflectorInterface
     */
    protected $inflector;

    /**
     * @var \Spryker\Glue\GlueJsonApiConvention\Formatter\JsonApiSchemaParametersFormatterInterface
     */
    protected $jsonApiSchemaParametersFormatter;

    /**
     * @param \Spryker\Glue\GlueJsonApiConvention\Dependency\External\GlueJsonApiConventionToInflectorInterface $inflector
     * @param \Spryker\Glue\GlueJsonApiConvention\Formatter\JsonApiSchemaParametersFormatterInterface $jsonApiSchemaParametersFormatter
     */
    public function __construct(
        GlueJsonApiConventionToInflectorInterface $inflector,
        JsonApiSchemaParametersFormatterInterface $jsonApiSchemaParametersFormatter
    ) {
        $this->inflector = $inflector;
        $this->jsonApiSchemaParametersFormatter = $jsonApiSchemaParametersFormatter;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(array $formattedData, ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): array
    {
        foreach ($apiApplicationSchemaContextTransfer->getResourceContexts() as $resourceContext) {
            $resourcePluginName = $resourceContext->getResourcePluginNameOrFail();
            if (is_subclass_of($resourcePluginName, JsonApiResourceInterface::class)) {
                $formattedData = $this->formatPaths($formattedData, $resourceContext);
                $formattedData = $this->jsonApiSchemaParametersFormatter->setComponentParameters($formattedData);
            }
        }

        $formattedData = $this->addErrorMessageSchema($formattedData);

        return $formattedData;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContext
     *
     * @return array<mixed>
     */
    protected function formatPaths(array $formattedData, ResourceContextTransfer $resourceContext): array
    {
        foreach ($this->getResourcePaths($resourceContext->getResourceTypeOrFail()) as $resourcePathKey) {
            $formattedData['paths'][$resourcePathKey] = $this->formatOperation($resourcePathKey, $formattedData['paths'][$resourcePathKey], $resourceContext);
        }

        return $formattedData;
    }

    /**
     * @param string $path
     * @param array<mixed> $pathData
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContext
     *
     * @return array<mixed>
     */
    protected function formatOperation(string $path, array $pathData, ResourceContextTransfer $resourceContext): array
    {
        foreach ($pathData as $key => $operation) {
            $resourceTypeWithConventionName = ucfirst($resourceContext->getResourceTypeOrFail());
            if (isset($operation['requestBody'])) {
                $operation = $this->formatRequestBody($operation, $resourceTypeWithConventionName);
            }

            $isGetCollection = $key === 'get' && $path === $this->getCollectionResourcePath($resourceContext->getResourceTypeOrFail());

            $operation = $this->formatResponses($operation, $resourceTypeWithConventionName, $isGetCollection);
            $operation = $this->jsonApiSchemaParametersFormatter->setOperationParameters($operation, $resourceContext);

            $pathData[$key] = $operation;
        }

        return $pathData;
    }

    /**
     * @param array<mixed> $operation
     * @param string $resourceType
     *
     * @return array<mixed>
     */
    protected function formatRequestBody(array $operation, string $resourceType): array
    {
        if (isset($operation['requestBody']) && isset($operation['requestBody']['content'])) {
            $contentTypes = [];
            foreach ($operation['requestBody']['content'] as $contentType => $value) {
                $contentTypes[(string)$contentType] = $value;
            }
            $operation['requestBody']['content'] = $this->addContent(
                $contentTypes,
                $resourceType . static::PART_REQUEST,
            );
        }

        return $operation;
    }

    /**
     * @param array<mixed> $operation
     * @param string $resourceTypeWithConventionName
     * @param bool $isGetCollection
     *
     * @return array<mixed>
     */
    protected function formatResponses(array $operation, string $resourceTypeWithConventionName, bool $isGetCollection): array
    {
        if (isset($operation['responses'])) {
            foreach ($operation['responses'] as $responseCode => $response) {
                $schemaObjectName = $resourceTypeWithConventionName
                    . static::PART_REST
                    . ($isGetCollection ? static::PART_COLLECTION : '')
                    . static::PART_RESPONSE;

                if ((int)$responseCode >= Response::HTTP_BAD_REQUEST || $responseCode === static::DEFAULT_RESPONSE) {
                    $schemaObjectName = static::ERROR_MESSAGE;
                }
                $response['content'] = $this->addContent($response['content'], $schemaObjectName);

                $operation['responses'][$responseCode] = $response;
            }
        }

        return $operation;
    }

    /**
     * @param array<string, mixed> $contentTypes
     * @param string $refName
     *
     * @return array<string, mixed>
     */
    protected function addContent(array $contentTypes, string $refName): array
    {
        $contentTypes[GlueJsonApiConventionConfig::HEADER_CONTENT_TYPE] = [
            static::KEY_SCHEMA => [
                static::KEY_REF => static::SCHEMA_REF . $refName,
            ],
        ];

        return $contentTypes;
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    protected function addErrorMessageSchema(array $formattedData): array
    {
        $formattedData['components']['schemas'] = array_replace_recursive(
            $formattedData['components']['schemas'],
            [
                static::ERROR_MESSAGE => [
                    'type' => 'object',
                    'properties' => [
                        'errors' => [
                            'type' => 'object',
                            'properties' => [
                                'status' => [
                                    'type' => 'integer',
                                ],
                                'code' => [
                                    'type' => 'string',
                                ],
                                'message' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        );

        return $formattedData;
    }

    /**
     * @param string $resourceType
     *
     * @return string
     */
    protected function getPathFromResourceType(string $resourceType): string
    {
        $resourceTypeExploded = explode('-', $resourceType);
        $resourceTypeCamelCased = array_map(function ($value) {
            return ucfirst($this->inflector->singularize($value));
        }, $resourceTypeExploded);

        $resourceId = sprintf(static::PATTERN_PATH_ID, lcfirst(implode('', $resourceTypeCamelCased)));

        return sprintf('/%s/%s', $resourceType, $resourceId);
    }

    /**
     * @param string $resourceType
     *
     * @return array<int, string>
     */
    protected function getResourcePaths(string $resourceType): array
    {
        return [
            $this->getCollectionResourcePath($resourceType),
            $this->getPathFromResourceType($resourceType),
        ];
    }

    /**
     * @param string $resourceType
     *
     * @return string
     */
    protected function getCollectionResourcePath(string $resourceType): string
    {
        return sprintf('/%s', $resourceType);
    }
}
