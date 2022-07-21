<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Expander;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\RelationshipPluginAnnotationsContextTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer\AnnotationAnalyzerInterface;

class RelationshipPluginAnnotationsContextExpander implements ContextExpanderInterface
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
        foreach ($apiApplicationSchemaContextTransfer->getRelationshipPluginsContexts()->getArrayCopy() as $relationshipPluginsContextTransfer) {
            /** @var \Generated\Shared\Transfer\RelationshipPluginAnnotationsContextTransfer|null $relationshipPluginAnnotationsContextTransfer */
            $relationshipPluginAnnotationsContextTransfer = $this->annotationAnalyzer->getResourceParametersFromResource(
                $relationshipPluginsContextTransfer->getResourcePluginName(),
                new RelationshipPluginAnnotationsContextTransfer(),
                null,
            );

            if ($relationshipPluginAnnotationsContextTransfer) {
                $relationshipPluginAnnotationsContextTransfer->setResourceType($relationshipPluginsContextTransfer->getResourceType());
                $relationshipPluginsContextTransfer->setRelationshipPluginAnnotationsContext($relationshipPluginAnnotationsContextTransfer);
            }
        }

        return $apiApplicationSchemaContextTransfer;
    }
}
