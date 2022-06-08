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
use Spryker\Glue\DocumentationGeneratorOpenApi\Exception\InvalidCustomRouteException;

class CustomRouteControllerAnnotationsContextExpander implements ContextExpanderInterface
{
    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

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
     * @throws \Spryker\Glue\DocumentationGeneratorOpenApi\Exception\InvalidCustomRouteException
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        foreach ($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->getArrayCopy() as $customRouteContextTransfer) {
            if (!isset($customRouteContextTransfer->getDefaults()[static::CONTROLLER])) {
                throw new InvalidCustomRouteException(
                    sprintf(
                        'Invalid custom route: %s needs to be defined',
                        static::CONTROLLER,
                    ),
                );
            }

            if (!is_array($customRouteContextTransfer->getDefaults()[static::CONTROLLER])) {
                return $apiApplicationSchemaContextTransfer;
            }

            /** @var \Generated\Shared\Transfer\PathAnnotationTransfer|null $pathAnnotationTransfer */
            $pathAnnotationTransfer = $this->annotationAnalyzer->getResourceParametersFromResource(
                $customRouteContextTransfer->getDefaults()[static::CONTROLLER][0],
                new PathAnnotationTransfer(),
                $customRouteContextTransfer->getDefaults()[static::CONTROLLER][1],
            );

            if ($pathAnnotationTransfer) {
                $customRouteContextTransfer->setPathAnnotation($pathAnnotationTransfer);
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
