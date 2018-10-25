<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer;

use Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SchemaPropertySpecificationComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SchemaSpecificationComponent;

class SchemaRenderer implements SchemaRendererInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer $schemaDataTransfer
     *
     * @return array
     */
    public function render(RestApiDocumentationSchemaDataTransfer $schemaDataTransfer): array
    {
        $schemaComponent = new SchemaSpecificationComponent();
        $schemaComponent->setName($schemaDataTransfer->getName());
        foreach ($schemaDataTransfer->getProperties() as $property) {
            $this->addSchemaProperty($property, $schemaComponent);
        }
        if ($schemaDataTransfer->getRequired()) {
            $schemaComponent->setRequired($schemaDataTransfer->getRequired());
        }

        if ($schemaComponent->isValid()) {
            return $schemaComponent->toArray();
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer $property
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SchemaSpecificationComponent $schemaComponent
     *
     * @return void
     */
    protected function addSchemaProperty(RestApiDocumentationSchemaPropertyTransfer $property, SchemaSpecificationComponent $schemaComponent): void
    {
        $propertyComponent = new SchemaPropertySpecificationComponent();
        $propertyComponent->setName($property->getName());
        if ($property->getType()) {
            $propertyComponent->setType($property->getType());
        }
        if ($property->getReference()) {
            $propertyComponent->setSchemaReference($property->getReference());
        }
        if ($property->getItemsReference()) {
            $propertyComponent->setItemsSchemaReference($property->getItemsReference());
        }

        if ($propertyComponent->isValid()) {
            $schemaComponent->addProperty($propertyComponent);
        }
    }
}
