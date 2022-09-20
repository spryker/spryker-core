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

class DeleteResourcePathMethodFormatter implements PathMethodFormatterInterface
{
    /**
     * @var string
     */
    protected const PATTERN_OPERATION_ID_DELETE_RESOURCE = 'delete-%s';

    /**
     * @var string
     */
    protected const PATTERN_SUMMARY_DELETE_RESOURCE = 'Delete %s.';

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
        if (!$pathAnnotationTransfer->getDelete()) {
            return $formattedData;
        }

        $resourceType = $pathAnnotationTransfer->getResourceTypeOrFail();
        $pathName = $this->openApiSpecificationPathMethodFormatter->getPathFromResourceType($resourceContextTransfer);

        $pathMethodComponentDataTransfer = (new PathMethodComponentDataTransfer())
            ->setResourceType($resourceType)
            ->setAnnotation($pathAnnotationTransfer->getDelete())
            ->setPatternOperationIdResource(static::PATTERN_OPERATION_ID_DELETE_RESOURCE)
            ->setDefaultResponseCode(Response::HTTP_NO_CONTENT)
            ->setIsGetCollection(false)
            ->setPathName($pathName)
            ->setIsProtected($resourceContextTransfer->getDeclaredMethodsOrFail()->getDeleteOrFail()->getIsProtected());

        $pathMethodData = $this->openApiSpecificationPathMethodFormatter->getPathMethodComponentData(
            $pathMethodComponentDataTransfer,
        );

        if (isset($pathMethodData['requestBody'])) {
            unset($pathMethodData['requestBody']);
        }
        if (!isset($pathMethodData['summary'])) {
            $pathMethodData['summary'] = $this->openApiSpecificationPathMethodFormatter->getDefaultMethodSummary(static::PATTERN_SUMMARY_DELETE_RESOURCE, $resourceType);
        }

        return $this->openApiSpecificationPathMethodFormatter->addPath(
            $pathMethodData,
            $pathName,
            strtolower(Request::METHOD_DELETE),
            $formattedData,
        );
    }
}
