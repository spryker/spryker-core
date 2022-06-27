<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Processor;

use Generated\Shared\Transfer\AnnotationTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Paths\OpenApiSpecificationPathMethodFormatterInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomRoutePathMethodFormatter implements CustomPathMethodFormatterInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_CONTROLLER_FIELD = '_controller';

    /**
     * @var string
     */
    protected const DEFAULT_METHOD_FIELD = '_method';

    /**
     * @var string
     */
    protected const DEFAULT_RESOURCE_NAME_FIELD = '_resourceName';

    /**
     * @var string
     */
    protected const PATTERN_OPERATION_ID_GET_RESOURCE = '-%s';

    /**
     * @var string
     */
    protected const PATTERN_SUMMARY_GET_RESOURCE = 'Get %s.';

    /**
     * @var string
     */
    protected const REQUEST_METHOD_GET = 'get';

    /**
     * @var string
     */
    protected const METHOD_GET_COLLECTION = 'getCollection';

    /**
     * @var string
     */
    protected const PATH_DATA_SUMMARY_FIELD = 'summary';

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Paths\OpenApiSpecificationPathMethodFormatterInterface
     */
    protected $openApiSpecificationPathMethodFormatter;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Paths\OpenApiSpecificationPathMethodFormatterInterface $openApiSpecificationPathMethodFormatter
     */
    public function __construct(OpenApiSpecificationPathMethodFormatterInterface $openApiSpecificationPathMethodFormatter)
    {
        $this->openApiSpecificationPathMethodFormatter = $openApiSpecificationPathMethodFormatter;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomRoutesContextTransfer $customRouteTransfer
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    public function format(CustomRoutesContextTransfer $customRouteTransfer, array $formattedData): array
    {
        $resourceType = $this->getResourceType($customRouteTransfer);
        $requestMethodName = $this->getRequestMethodFromAnnotation($customRouteTransfer);
        $operationIdPattern = $requestMethodName . static::PATTERN_OPERATION_ID_GET_RESOURCE;

        $pathMethodData = $this->openApiSpecificationPathMethodFormatter->getPathMethodComponentData(
            $resourceType,
            $this->getAnnotationTransfer($customRouteTransfer),
            $operationIdPattern,
            Response::HTTP_OK,
        );

        $pathMethodData = $this->fixEmptySummary($resourceType, $pathMethodData);

        return $this->openApiSpecificationPathMethodFormatter->addPath(
            $pathMethodData,
            $customRouteTransfer->getPathOrFail(),
            $requestMethodName,
            $formattedData,
        );
    }

    /**
     * @param string $resourceType
     * @param array<mixed> $pathMethodData
     *
     * @return array<mixed>
     */
    protected function fixEmptySummary(string $resourceType, array $pathMethodData): array
    {
        if (!$pathMethodData[static::PATH_DATA_SUMMARY_FIELD]) {
            $pathMethodData[static::PATH_DATA_SUMMARY_FIELD] = $this->openApiSpecificationPathMethodFormatter
                ->getDefaultMethodSummary(static::PATTERN_SUMMARY_GET_RESOURCE, $resourceType);
        }

        return $pathMethodData;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomRoutesContextTransfer $customRouteTransfer
     *
     * @return string
     */
    protected function getResourceType(CustomRoutesContextTransfer $customRouteTransfer): string
    {
        $defaultsData = $customRouteTransfer->getDefaults();
        if (isset($defaultsData[static::DEFAULT_RESOURCE_NAME_FIELD])) {
            return $defaultsData[static::DEFAULT_RESOURCE_NAME_FIELD];
        }

        $path = ltrim($customRouteTransfer->getPathOrFail(), '/');
        $segments = explode('/', $path);

        return $segments[0];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomRoutesContextTransfer $customRouteTransfer
     *
     * @return string
     */
    protected function getRequestMethodFromAnnotation(CustomRoutesContextTransfer $customRouteTransfer): string
    {
        $defaultsData = $customRouteTransfer->getDefaults();
        $method = $defaultsData[static::DEFAULT_METHOD_FIELD];

        return ($method === static::METHOD_GET_COLLECTION) ? static::REQUEST_METHOD_GET : strtolower($method);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomRoutesContextTransfer $customRouteTransfer
     *
     * @return \Generated\Shared\Transfer\AnnotationTransfer
     */
    protected function getAnnotationTransfer(CustomRoutesContextTransfer $customRouteTransfer): AnnotationTransfer
    {
        $defaultsData = $customRouteTransfer->getDefaults();

        if (
            $defaultsData[static::DEFAULT_CONTROLLER_FIELD] !== null &&
            strtolower($defaultsData[static::DEFAULT_METHOD_FIELD]) === static::REQUEST_METHOD_GET
        ) {
            $pathAnnotationsTransfer = $customRouteTransfer->getPathAnnotationOrFail();
            $modifiedProperties = array_keys($pathAnnotationsTransfer->modifiedToArray(true, true));

            foreach ($modifiedProperties as $modifiedProperty) {
                if (strpos($modifiedProperty, static::REQUEST_METHOD_GET) !== 0) {
                    continue;
                }

                return $pathAnnotationsTransfer->offsetGet($modifiedProperty);
            }
        }

        return $customRouteTransfer->getPathAnnotationOrFail()->offsetGet($defaultsData[static::DEFAULT_METHOD_FIELD]);
    }
}
