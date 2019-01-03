<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer;

use Generated\Shared\Transfer\SchemaComponentTransfer;
use Generated\Shared\Transfer\SchemaDataTransfer;
use Generated\Shared\Transfer\SchemaPropertyComponentTransfer;
use Generated\Shared\Transfer\SchemaPropertyTransfer;
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
     * @param \Generated\Shared\Transfer\SchemaDataTransfer $schemaDataTransfer
     *
     * @return array
     */
    public function render(SchemaDataTransfer $schemaDataTransfer): array
    {
        $schemaComponentTransfer = new SchemaComponentTransfer();
        $schemaComponentTransfer->setName($schemaDataTransfer->getName());
        foreach ($schemaDataTransfer->getProperties() as $property) {
            $this->addSchemaProperty($schemaComponentTransfer, $property);
        }
        if ($schemaDataTransfer->getRequired()) {
            $schemaComponentTransfer->setRequired($schemaDataTransfer->getRequired());
        }

        $this->schemaSpecificationComponent->setSchemaComponentTransfer($schemaComponentTransfer);

        return $this->schemaSpecificationComponent->getSpecificationComponentData();
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaComponentTransfer $schemaComponent
     * @param \Generated\Shared\Transfer\SchemaPropertyTransfer $property
     *
     * @return void
     */
    protected function addSchemaProperty(SchemaComponentTransfer $schemaComponent, SchemaPropertyTransfer $property): void
    {
        $schemaPropertyComponentTransfer = new SchemaPropertyComponentTransfer();
        $schemaPropertyComponentTransfer->setName($property->getName());
        $schemaPropertyComponentTransfer->setIsNullable($property->getIsNullable());
        if ($property->getType()) {
            $schemaPropertyComponentTransfer->setType($property->getType());
        }
        if ($property->getReference()) {
            $schemaPropertyComponentTransfer->setSchemaReference($property->getReference());
        }
        if ($property->getItemsReference()) {
            $schemaPropertyComponentTransfer->setItemsSchemaReference($property->getItemsReference());
        }
        if ($property->getItemsType()) {
            $schemaPropertyComponentTransfer->setItemsType($property->getItemsType());
        }

        $this->schemaPropertySpecificationComponent->setSchemaPropertyComponentTransfer($schemaPropertyComponentTransfer);
        $schemaPropertySpecificationData = $this->schemaPropertySpecificationComponent->getSpecificationComponentData();

        if ($schemaPropertySpecificationData) {
            $schemaComponent->addProperty($schemaPropertySpecificationData);
        }
    }
}
