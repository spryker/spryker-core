<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Processor;

use Generated\Shared\Transfer\PathMethodComponentDataTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Paths\OpenApiSpecificationPathMethodFormatterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetCollectionResourcePathMethodFormatter implements PathMethodFormatterInterface
{
    /**
     * @var string
     */
    protected const PATTERN_OPERATION_ID_GET_COLLECTION = 'get-collection-of-%s';

    /**
     * @var string
     */
    protected const PATTERN_SUMMARY_GET_COLLECTION = 'Get collection of %s.';

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
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContextTransfer
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    public function format(ResourceContextTransfer $resourceContextTransfer, array $formattedData): array
    {
        $pathAnnotationTransfer = $resourceContextTransfer->getPathAnnotationOrFail();
        if (!$pathAnnotationTransfer->getGetCollection()) {
            return $formattedData;
        }

        $resourceType = $pathAnnotationTransfer->getResourceTypeOrFail();
        $pathName = sprintf('/%s', $resourceType);

        $pathMethodComponentDataTransfer = (new PathMethodComponentDataTransfer())
            ->setResourceType($resourceType)
            ->setAnnotation($pathAnnotationTransfer->getGetCollection())
            ->setPatternOperationIdResource(static::PATTERN_OPERATION_ID_GET_COLLECTION)
            ->setDefaultResponseCode(Response::HTTP_OK)
            ->setIsGetCollection(true)
            ->setPathName($pathName)
            ->setIsProtected($resourceContextTransfer->getDeclaredMethodsOrFail()->getGetCollectionOrFail()->getIsProtected());

        $pathMethodData = $this->openApiSpecificationPathMethodFormatter->getPathMethodComponentData(
            $pathMethodComponentDataTransfer,
        );

        if (!isset($pathMethodData['summary'])) {
            $pathMethodData['summary'] = $this->openApiSpecificationPathMethodFormatter
                ->getDefaultMethodSummary(static::PATTERN_SUMMARY_GET_COLLECTION, $resourceType);
        }

        return $this->openApiSpecificationPathMethodFormatter->addPath(
            $pathMethodData,
            $pathName,
            strtolower(Request::METHOD_GET),
            $formattedData,
        );
    }
}
