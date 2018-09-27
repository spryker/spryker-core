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
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface;

class SchemaRenderer implements SchemaRendererInterface
{
    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface
     */
    protected $specificationComponentValidator;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface $specificationComponentValidator
     */
    public function __construct(SpecificationComponentValidatorInterface $specificationComponentValidator)
    {
        $this->specificationComponentValidator = $specificationComponentValidator;
    }

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

        if ($this->specificationComponentValidator->isValid($schemaComponent)) {
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

        if ($this->specificationComponentValidator->isValid($propertyComponent)) {
            $schemaComponent->addProperty($propertyComponent);
        }
    }
}
