<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer;

use Generated\Shared\Transfer\OpenApiSpecificationSchemaComponentTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationSchemaDataTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationSchemaPropertyComponentTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationSchemaPropertyTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaPropertySpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaSpecificationComponentInterface;

class SchemaRenderer implements SchemaRendererInterface
{
    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaSpecificationComponentInterface
     */
    protected $schemaSpecificationComponent;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaPropertySpecificationComponentInterface
     */
    protected $schemaPropertySpecificationComponent;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaSpecificationComponentInterface $schemaSpecificationComponent
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaPropertySpecificationComponentInterface $schemaPropertySpecificationComponent
     */
    public function __construct(
        SchemaSpecificationComponentInterface $schemaSpecificationComponent,
        SchemaPropertySpecificationComponentInterface $schemaPropertySpecificationComponent
    ) {
        $this->schemaSpecificationComponent = $schemaSpecificationComponent;
        $this->schemaPropertySpecificationComponent = $schemaPropertySpecificationComponent;
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationSchemaDataTransfer $schemaDataTransfer
     *
     * @return array
     */
    public function render(OpenApiSpecificationSchemaDataTransfer $schemaDataTransfer): array
    {
        $schemaComponentTransfer = new OpenApiSpecificationSchemaComponentTransfer();
        $schemaComponentTransfer->setName($schemaDataTransfer->getName());
        foreach ($schemaDataTransfer->getProperties() as $property) {
            $this->addSchemaProperty($schemaComponentTransfer, $property);
        }
        if ($schemaDataTransfer->getRequired()) {
            $schemaComponentTransfer->setRequired($schemaDataTransfer->getRequired());
        }

        $this->schemaSpecificationComponent->setSchemaComponentTransfer($schemaComponentTransfer);

        if ($this->schemaSpecificationComponent->isValid()) {
            return $this->schemaSpecificationComponent->getSpecificationComponentData();
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationSchemaComponentTransfer $schemaComponent
     * @param \Generated\Shared\Transfer\OpenApiSpecificationSchemaPropertyTransfer $property
     *
     * @return void
     */
    protected function addSchemaProperty(OpenApiSpecificationSchemaComponentTransfer $schemaComponent, OpenApiSpecificationSchemaPropertyTransfer $property): void
    {
        $schemaPropertyComponentTransfer = new OpenApiSpecificationSchemaPropertyComponentTransfer();
        $schemaPropertyComponentTransfer->setName($property->getName());
        if ($property->getType()) {
            $schemaPropertyComponentTransfer->setType($property->getType());
        }
        if ($property->getReference()) {
            $schemaPropertyComponentTransfer->setSchemaReference($property->getReference());
        }
        if ($property->getItemsReference()) {
            $schemaPropertyComponentTransfer->setItemsSchemaReference($property->getItemsReference());
        }

        $this->schemaPropertySpecificationComponent->setSchemaPropertyComponentTransfer($schemaPropertyComponentTransfer);

        if ($this->schemaPropertySpecificationComponent->isValid()) {
            $schemaComponent->addProperty($this->schemaPropertySpecificationComponent->getSpecificationComponentData());
        }
    }
}
