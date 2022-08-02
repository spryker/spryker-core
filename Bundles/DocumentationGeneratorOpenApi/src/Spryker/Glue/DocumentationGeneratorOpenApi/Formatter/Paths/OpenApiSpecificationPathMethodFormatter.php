<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Paths;

use Generated\Shared\Transfer\AnnotationTransfer;
use Generated\Shared\Transfer\PathMethodComponentDataTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\ResourceTransferAnalyzerInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External\DocumentationGeneratorOpenApiToInflectorInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component\PathParameterSpecificationComponentInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component\PathRequestSpecificationComponentInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component\PathResponseSpecificationComponentInterface;

class OpenApiSpecificationPathMethodFormatter implements OpenApiSpecificationPathMethodFormatterInterface
{
    /**
     * @var string
     */
    protected const PATTERN_SCHEMA_REFERENCE = '#/components/schemas/%s';

    /**
     * @var string
     */
    protected const PATTERN_PATH_ID = '{%sId}';

    /**
     * @var string
     */
    protected const KEY_SUMMARY = 'summary';

    /**
     * @var string
     */
    protected const KEY_OPERATION_ID = 'operationId';

    /**
     * @var string
     */
    protected const KEY_PARAMETERS = 'parameters';

    /**
     * @var string
     */
    protected const KEY_REQUEST_BODY = 'requestBody';

    /**
     * @var string
     */
    protected const KEY_RESPONSES = 'responses';

    /**
     * @var string
     */
    protected const KEY_DEFAULT_RESPONSE_CODE = 'defaultResponseCode';

    /**
     * @var string
     */
    protected const KEY_PATHS = 'paths';

    /**
     * @var string
     */
    protected const KEY_RESPONSE_ATTRIBUTES_CLASS_NAME = 'response_attributes_class_name';

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\ResourceTransferAnalyzerInterface
     */
    protected $resourceTransferAnalyzer;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External\DocumentationGeneratorOpenApiToInflectorInterface
     */
    protected $inflector;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component\PathResponseSpecificationComponentInterface
     */
    protected $pathResponseSpecificationComponent;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component\PathRequestSpecificationComponentInterface
     */
    protected $pathRequestSpecificationComponent;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component\PathParameterSpecificationComponentInterface
     */
    protected $pathParameterSpecificationComponent;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\ResourceTransferAnalyzerInterface $resourceTransferAnalyzer
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External\DocumentationGeneratorOpenApiToInflectorInterface $inflector
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component\PathResponseSpecificationComponentInterface $pathResponseSpecificationComponent
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component\PathRequestSpecificationComponentInterface $pathRequestSpecificationComponent
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component\PathParameterSpecificationComponentInterface $pathParameterSpecificationComponent
     */
    public function __construct(
        ResourceTransferAnalyzerInterface $resourceTransferAnalyzer,
        DocumentationGeneratorOpenApiToInflectorInterface $inflector,
        PathResponseSpecificationComponentInterface $pathResponseSpecificationComponent,
        PathRequestSpecificationComponentInterface $pathRequestSpecificationComponent,
        PathParameterSpecificationComponentInterface $pathParameterSpecificationComponent
    ) {
        $this->resourceTransferAnalyzer = $resourceTransferAnalyzer;
        $this->inflector = $inflector;
        $this->pathResponseSpecificationComponent = $pathResponseSpecificationComponent;
        $this->pathRequestSpecificationComponent = $pathRequestSpecificationComponent;
        $this->pathParameterSpecificationComponent = $pathParameterSpecificationComponent;
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodComponentDataTransfer $pathMethodComponentDataTransfer
     *
     * @return array<mixed>
     */
    public function getPathMethodComponentData(PathMethodComponentDataTransfer $pathMethodComponentDataTransfer): array
    {
        $operationId = sprintf($pathMethodComponentDataTransfer->getPatternOperationIdResourceOrFail(), $pathMethodComponentDataTransfer->getResourceType());
        $annotationTransfer = $pathMethodComponentDataTransfer->getAnnotationOrFail();
        $pathMethodData = [];
        $pathMethodData[static::KEY_OPERATION_ID] = $operationId;
        $pathMethodData = array_merge($pathMethodData, $annotationTransfer->modifiedToArray());

        if (is_array($pathMethodData[static::KEY_SUMMARY])) {
            $pathMethodData[static::KEY_SUMMARY] = $pathMethodData[static::KEY_SUMMARY][0];
        }

        $specificationComponentData = $this->pathParameterSpecificationComponent->getSpecificationComponentData(
            $pathMethodData,
            $pathMethodComponentDataTransfer->getPathNameOrFail(),
        );

        if ($specificationComponentData) {
            $pathMethodData[static::KEY_PARAMETERS] = $specificationComponentData;
        }

        if (
            !$this->isGetMethod($operationId) &&
            $this->resourceTransferAnalyzer->isRequestSchemaRequired($annotationTransfer->getResponseAttributesClassNameOrFail())
        ) {
            $requestReference = $this->getRequestName($annotationTransfer);
            $pathMethodData[static::KEY_REQUEST_BODY] = $this->pathRequestSpecificationComponent->getSpecificationComponentData($pathMethodData, $requestReference);
        }

        $responseReference = $pathMethodComponentDataTransfer->getIsGetCollection()
            ? $this->getResponseCollectionName($annotationTransfer)
            : $this->getResponseName($annotationTransfer);
        $pathMethodData[static::KEY_RESPONSES] = $this->pathResponseSpecificationComponent->getSpecificationComponentData(
            array_merge($pathMethodData, [static::KEY_DEFAULT_RESPONSE_CODE => $pathMethodComponentDataTransfer->getDefaultResponseCode()]),
            $responseReference,
        );

        return $pathMethodData;
    }

    /**
     * @param string $pattern
     * @param string $resourceType
     *
     * @return string
     */
    public function getDefaultMethodSummary(string $pattern, string $resourceType): string
    {
        return sprintf($pattern, str_replace('-', ' ', $resourceType));
    }

    /**
     * @param array<mixed> $pathMethodData
     * @param string $pathName
     * @param string $methodName
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    public function addPath(array $pathMethodData, string $pathName, string $methodName, array $formattedData): array
    {
        $methodsData = [$methodName => $pathMethodData];

        if (isset($formattedData[static::KEY_PATHS][$pathName])) {
            $methodsData = $formattedData[static::KEY_PATHS][$pathName] ?? [];
            $methodsData[$methodName] = $pathMethodData;
        }

        if (isset($methodsData[$methodName][static::KEY_RESPONSE_ATTRIBUTES_CLASS_NAME])) {
            unset($methodsData[$methodName][static::KEY_RESPONSE_ATTRIBUTES_CLASS_NAME]);
        }

        $formattedData[static::KEY_PATHS][$pathName] = $methodsData;

        return $formattedData;
    }

    /**
     * @param string $resourceType
     *
     * @return string
     */
    public function getPathFromResourceType(string $resourceType): string
    {
        $resourceTypeExploded = explode('-', $resourceType);
        $resourceTypeCamelCased = array_map(function ($value) {
            return ucfirst($this->inflector->singularize($value));
        }, $resourceTypeExploded);

        $resourceId = sprintf(static::PATTERN_PATH_ID, lcfirst(implode('', $resourceTypeCamelCased)));

        return sprintf('/%s/%s', $resourceType, $resourceId);
    }

    /**
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     *
     * @return string
     */
    protected function getRequestName(AnnotationTransfer $annotationTransfer): string
    {
        $requestSchemaName = $this->resourceTransferAnalyzer->createRequestAttributesSchemaNameFromTransferClassName($annotationTransfer->getResponseAttributesClassNameOrFail());
        $requestSchemaName = sprintf(static::PATTERN_SCHEMA_REFERENCE, $requestSchemaName);

        return $requestSchemaName;
    }

    /**
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     *
     * @return string
     */
    protected function getResponseName(AnnotationTransfer $annotationTransfer): string
    {
        $requestSchemaName = $this->resourceTransferAnalyzer->createResponseResourceSchemaNameFromTransferClassName($annotationTransfer->getResponseAttributesClassNameOrFail());
        $requestSchemaName = sprintf(static::PATTERN_SCHEMA_REFERENCE, $requestSchemaName);

        return $requestSchemaName;
    }

    /**
     * @param \Generated\Shared\Transfer\AnnotationTransfer $annotationTransfer
     *
     * @return string
     */
    protected function getResponseCollectionName(AnnotationTransfer $annotationTransfer): string
    {
        $requestSchemaName = $this->resourceTransferAnalyzer->createResponseCollectionSchemaNameFromTransferClassName($annotationTransfer->getResponseAttributesClassNameOrFail());
        $requestSchemaName = sprintf(static::PATTERN_SCHEMA_REFERENCE, $requestSchemaName);

        return $requestSchemaName;
    }

    /**
     * @param string $operationId
     *
     * @return bool
     */
    protected function isGetMethod(string $operationId): bool
    {
        return stripos($operationId, 'get') === 0;
    }
}
