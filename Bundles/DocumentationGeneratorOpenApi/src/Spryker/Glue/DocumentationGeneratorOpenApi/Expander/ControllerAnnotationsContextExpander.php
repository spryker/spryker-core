<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\PathAnnotationTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\AnnotationAnalyzerInterface;

class ControllerAnnotationsContextExpander implements ContextExpanderInterface
{
    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\AnnotationAnalyzerInterface
     */
    protected $annotationAnalyzer;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\AnnotationAnalyzerInterface $annotationAnalyzer
     */
    public function __construct(AnnotationAnalyzerInterface $annotationAnalyzer)
    {
        $this->annotationAnalyzer = $annotationAnalyzer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        foreach ($apiApplicationSchemaContextTransfer->getResourceContexts()->getArrayCopy() as $resourceContextTransfer) {
            /** @var \Generated\Shared\Transfer\PathAnnotationTransfer|null $pathAnnotationTransfer */
            $pathAnnotationTransfer = $this->annotationAnalyzer->getResourceParametersFromResource(
                $resourceContextTransfer->getController(),
                new PathAnnotationTransfer(),
                null,
            );

            if ($pathAnnotationTransfer) {
                $pathAnnotationTransfer->setResourceType($resourceContextTransfer->getResourceType());
                $resourceContextTransfer->setPathAnnotation($pathAnnotationTransfer);
            }
        }

        return $this->filterByPathAnnotation($apiApplicationSchemaContextTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    protected function filterByPathAnnotation(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        $filteredResourceContextsData = array_filter(
            $apiApplicationSchemaContextTransfer->getResourceContexts()->getArrayCopy(),
            function (ResourceContextTransfer $resourceContextTransfer) {
                return $resourceContextTransfer->getPathAnnotation() !== null;
            },
        );

        return $apiApplicationSchemaContextTransfer->setResourceContexts(new ArrayObject($filteredResourceContextsData));
    }
}
