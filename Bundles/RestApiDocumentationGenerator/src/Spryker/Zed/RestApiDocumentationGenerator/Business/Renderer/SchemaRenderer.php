<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer;

use Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SchemaComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SchemaPropertyComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidatorInterface;

class SchemaRenderer implements SchemaRendererInterface
{
    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidatorInterface
     */
    protected $validator;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\ComponentValidatorInterface $validator
     */
    public function __construct(ComponentValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSchemaDataTransfer $schemaDataTransfer
     *
     * @return array
     */
    public function render(RestApiDocumentationSchemaDataTransfer $schemaDataTransfer): array
    {
        $schemaComponent = new SchemaComponent();
        $schemaComponent->setName($schemaDataTransfer->getName());
        foreach ($schemaDataTransfer->getProperties() as $property) {
            $this->addSchemaProperty($property, $schemaComponent);
        }
        if ($schemaDataTransfer->getRequired()) {
            $schemaComponent->setRequired($schemaDataTransfer->getRequired());
        }

        if ($this->validator->isValid($schemaComponent)) {
            return $schemaComponent->toArray();
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSchemaPropertyTransfer $property
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SchemaComponent $schemaComponent
     *
     * @return void
     */
    protected function addSchemaProperty(RestApiDocumentationSchemaPropertyTransfer $property, SchemaComponent $schemaComponent): void
    {
        $propertyComponent = new SchemaPropertyComponent();
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

        if ($this->validator->isValid($propertyComponent)) {
            $schemaComponent->addProperty($propertyComponent);
        }
    }
}
