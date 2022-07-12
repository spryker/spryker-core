<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Formatter;

use Generated\Shared\Transfer\AnnotationTransfer;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\PathAnnotationTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\GlueRestApiConvention\Dependency\External\GlueRestApiConventionToInflectorInterface;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RestResourceInterface;
use Symfony\Component\HttpFoundation\Response;

class RestApiSchemaFormatter implements SchemaFormatterInterface
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
    protected const REST_API_CONVENTION_NAME = 'RestApiConvention';

    /**
     * @var string
     */
    protected const PART_REQUEST = 'RestRequestAttributes';

    /**
     * @var string
     */
    protected const PART_ATTRIBUTES = 'RestAttributes';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'RestErrorMessage';

    /**
     * @var string
     */
    protected const DEFAULT_RESPONSE = 'default';

    /**
     * @var string
     */
    protected const SCHEMA_REF = '#/components/schemas/';

    /**
     * @var string
     */
    protected const HTTP_METHOD_GET = 'get';

    /**
     * @var string
     */
    protected const TRANSFER_NAME_PARTIAL_TRANSFER = 'Transfer';

    /**
     * @var string
     */
    protected const TRANSFER_PROPERTY_OR_FAIL_PART = 'OrFail';

    /**
     * @var \Spryker\Glue\GlueRestApiConvention\Dependency\External\GlueRestApiConventionToInflectorInterface
     */
    protected $inflector;

    /**
     * @var \Spryker\Glue\GlueRestApiConvention\Formatter\RestApiSchemaParametersFormatterInterface
     */
    protected $restApiSchemaParametersFormatter;

    /**
     * @var \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\GlueRestApiConvention\Dependency\External\GlueRestApiConventionToInflectorInterface $inflector
     * @param \Spryker\Glue\GlueRestApiConvention\Formatter\RestApiSchemaParametersFormatterInterface $restApiSchemaParametersFormatter
     * @param \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig $config
     */
    public function __construct(
        GlueRestApiConventionToInflectorInterface $inflector,
        RestApiSchemaParametersFormatterInterface $restApiSchemaParametersFormatter,
        GlueRestApiConventionConfig $config
    ) {
        $this->inflector = $inflector;
        $this->restApiSchemaParametersFormatter = $restApiSchemaParametersFormatter;
        $this->config = $config;
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
            $resourcePluginName = $resourceContext->getResourcePluginName();
            if (new $resourcePluginName() instanceof RestResourceInterface) {
                $formattedData = $this->formatPaths($formattedData, $resourceContext);
                $formattedData = $this->restApiSchemaParametersFormatter->setComponentParameters($formattedData);
            }
        }

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
            if (isset($formattedData['paths'][$resourcePathKey])) {
                $formattedData['paths'][$resourcePathKey] = $this->formatOperation($resourcePathKey, $formattedData['paths'][$resourcePathKey], $resourceContext);
            }
        }

        return $formattedData;
    }

    /**
     * @param string $path
     * @param array<mixed> $pathItem
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContext
     *
     * @return array<mixed>
     */
    protected function formatOperation(string $path, array $pathItem, ResourceContextTransfer $resourceContext): array
    {
        foreach ($pathItem as $key => $operation) {
            if (isset($operation['requestBody'])) {
                $operation = $this->formatRequestBody($operation, $resourceContext->getResourceTypeOrFail());
            }

            $isGetCollection = $key === static::HTTP_METHOD_GET && $path === $this->getCollectionResourcePath($resourceContext->getResourceTypeOrFail());

            $resourceAttributesClassName = $this->getResourceAttributesClassName($resourceContext->getPathAnnotationOrFail(), $key, $isGetCollection);

            $operation = $this->formatResponses($operation, $resourceAttributesClassName, $isGetCollection);
            $operation = $this->restApiSchemaParametersFormatter->setOperationParameters($operation, $resourceContext);

            $pathItem[$key] = $operation;
        }

        return $pathItem;
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
                ucfirst($resourceType) . static::PART_REQUEST,
            );
        }

        return $operation;
    }

    /**
     * @param array<mixed> $operation
     * @param string $resourceAttributesClassName
     * @param bool $isCollection
     *
     * @return array<mixed>
     */
    protected function formatResponses(array $operation, string $resourceAttributesClassName, bool $isCollection): array
    {
        if (isset($operation['responses'])) {
            foreach ($operation['responses'] as $responseCode => $response) {
                if ((int)$responseCode >= Response::HTTP_BAD_REQUEST || $responseCode === static::DEFAULT_RESPONSE) {
                    $operation['responses'][$responseCode]['content'] = $this->addContent($response['content'], static::ERROR_MESSAGE);

                    continue;
                }

                $operation['responses'][$responseCode]['content'] = $isCollection
                    ? $this->addCollectionContent($response['content'], $resourceAttributesClassName)
                    : $this->addContent($response['content'], $resourceAttributesClassName);
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
        $contentTypes[$this->config->getDefaultFormat()] = [
            static::KEY_SCHEMA => [
                static::KEY_REF => static::SCHEMA_REF . $refName,
            ],
        ];

        return $contentTypes;
    }

    /**
     * @param array<string, mixed> $contentTypes
     * @param string $refName
     *
     * @return array<string, mixed>
     */
    protected function addCollectionContent(array $contentTypes, string $refName): array
    {
        $contentTypes[$this->config->getDefaultFormat()] = [
            static::KEY_SCHEMA => [
                'type' => 'array',
                'items' => [static::KEY_REF => static::SCHEMA_REF . $refName],
            ],
        ];

        return $contentTypes;
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

    /**
     * @param \Generated\Shared\Transfer\PathAnnotationTransfer $pathAnnotationTransfer
     * @param string $methodName
     * @param bool $isCollection
     *
     * @return string
     */
    protected function getResourceAttributesClassName(
        PathAnnotationTransfer $pathAnnotationTransfer,
        string $methodName,
        bool $isCollection
    ): string {
        $annotationTransfer = $this->resolveAnnotationTransfer($pathAnnotationTransfer, $methodName, $isCollection);

        /** @var string $responseAttributesClassName */
        $responseAttributesClassName = $annotationTransfer->getResponseAttributesClassName();
        if ($responseAttributesClassName) {
            $responseAttributesClassName = $this->getResponseAttributesName($responseAttributesClassName);
        }

        return $responseAttributesClassName;
    }

    /**
     * @param \Generated\Shared\Transfer\PathAnnotationTransfer $pathAnnotationTransfer
     * @param string $methodName
     * @param bool $isCollection
     *
     * @return \Generated\Shared\Transfer\AnnotationTransfer
     */
    protected function resolveAnnotationTransfer(
        PathAnnotationTransfer $pathAnnotationTransfer,
        string $methodName,
        bool $isCollection
    ): AnnotationTransfer {
        if ($methodName === static::HTTP_METHOD_GET && !$isCollection) {
            return $pathAnnotationTransfer->getGetResourceByIdOrFail();
        }
        if ($isCollection) {
            return $pathAnnotationTransfer->getGetCollectionOrFail();
        }

        $annotationPropertyName = static::HTTP_METHOD_GET . ucfirst($methodName) . static::TRANSFER_PROPERTY_OR_FAIL_PART;

        return $pathAnnotationTransfer->$annotationPropertyName();
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function getResponseAttributesName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_TRANSFER,
            '',
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function getTransferClassNamePartial(string $transferClassName): string
    {
        $transferClassNameExploded = explode('\\', $transferClassName);

        /** @phpstan-var string */
        return end($transferClassNameExploded);
    }
}
