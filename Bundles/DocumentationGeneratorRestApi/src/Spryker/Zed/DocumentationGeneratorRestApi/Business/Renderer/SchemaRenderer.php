<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer;

use Generated\Shared\Transfer\SchemaComponentTransfer;
use Generated\Shared\Transfer\SchemaDataTransfer;
use Generated\Shared\Transfer\SchemaItemsComponentTransfer;
use Generated\Shared\Transfer\SchemaItemsTransfer;
use Generated\Shared\Transfer\SchemaPropertyComponentTransfer;
use Generated\Shared\Transfer\SchemaPropertyTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaItemsSpecificationComponentInterface;
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
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaItemsSpecificationComponentInterface
     */
    protected $schemaItemsSpecificationComponent;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaSpecificationComponentInterface $schemaSpecificationComponent
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaPropertySpecificationComponentInterface $schemaPropertySpecificationComponent
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\SchemaItemsSpecificationComponentInterface $schemaItemsSpecificationComponent
     */
    public function __construct(
        SchemaSpecificationComponentInterface $schemaSpecificationComponent,
        SchemaPropertySpecificationComponentInterface $schemaPropertySpecificationComponent,
        SchemaItemsSpecificationComponentInterface $schemaItemsSpecificationComponent
    ) {
        $this->schemaSpecificationComponent = $schemaSpecificationComponent;
        $this->schemaPropertySpecificationComponent = $schemaPropertySpecificationComponent;
        $this->schemaItemsSpecificationComponent = $schemaItemsSpecificationComponent;
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

        if ($schemaDataTransfer->getItems()) {
            $this->addRelationshipSchemaItems($schemaComponentTransfer, $schemaDataTransfer->getItems());
        }
        if ($schemaDataTransfer->getType()) {
            $schemaComponentTransfer->setType($schemaDataTransfer->getType());
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

        $schemaPropertyComponentTransfer = $this->addType($schemaPropertyComponentTransfer, $property);
        $schemaPropertyComponentTransfer = $this->addReference($schemaPropertyComponentTransfer, $property);
        $schemaPropertyComponentTransfer = $this->addOneOf($schemaPropertyComponentTransfer, $property);
        $schemaPropertyComponentTransfer = $this->addItemsType($schemaPropertyComponentTransfer, $property);
        $schemaPropertyComponentTransfer = $this->addItemsReference($schemaPropertyComponentTransfer, $property);

        $this->schemaPropertySpecificationComponent->setSchemaPropertyComponentTransfer($schemaPropertyComponentTransfer);
        $schemaPropertySpecificationData = $this->schemaPropertySpecificationComponent->getSpecificationComponentData();

        if ($schemaPropertySpecificationData) {
            $schemaComponent->addProperty($schemaPropertySpecificationData);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     * @param \Generated\Shared\Transfer\SchemaPropertyTransfer $property
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyComponentTransfer
     */
    protected function addOneOf(
        SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer,
        SchemaPropertyTransfer $property
    ): SchemaPropertyComponentTransfer {
        if ($property->getOneOf()) {
            $schemaPropertyComponentTransfer->setOneOf($property->getOneOf());
        }

        return $schemaPropertyComponentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     * @param \Generated\Shared\Transfer\SchemaPropertyTransfer $property
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyComponentTransfer
     */
    protected function addType(
        SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer,
        SchemaPropertyTransfer $property
    ): SchemaPropertyComponentTransfer {
        if ($property->getType()) {
            $schemaPropertyComponentTransfer->setType($property->getType());
        }

        return $schemaPropertyComponentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     * @param \Generated\Shared\Transfer\SchemaPropertyTransfer $property
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyComponentTransfer
     */
    protected function addReference(
        SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer,
        SchemaPropertyTransfer $property
    ): SchemaPropertyComponentTransfer {
        if ($property->getReference()) {
            $schemaPropertyComponentTransfer->setSchemaReference($property->getReference());
        }

        return $schemaPropertyComponentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     * @param \Generated\Shared\Transfer\SchemaPropertyTransfer $property
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyComponentTransfer
     */
    protected function addItemsType(
        SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer,
        SchemaPropertyTransfer $property
    ): SchemaPropertyComponentTransfer {
        if ($property->getItemsType()) {
            $schemaPropertyComponentTransfer->setItemsType($property->getItemsType());
        }

        return $schemaPropertyComponentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer
     * @param \Generated\Shared\Transfer\SchemaPropertyTransfer $property
     *
     * @return \Generated\Shared\Transfer\SchemaPropertyComponentTransfer
     */
    protected function addItemsReference(
        SchemaPropertyComponentTransfer $schemaPropertyComponentTransfer,
        SchemaPropertyTransfer $property
    ): SchemaPropertyComponentTransfer {
        if ($property->getItemsReference()) {
            $schemaPropertyComponentTransfer->setItemsSchemaReference($property->getItemsReference());
        }

        return $schemaPropertyComponentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchemaComponentTransfer $schemaComponent
     * @param \Generated\Shared\Transfer\SchemaItemsTransfer $items
     *
     * @return void
     */
    protected function addRelationshipSchemaItems(SchemaComponentTransfer $schemaComponent, SchemaItemsTransfer $items): void
    {
        $schemaPropertyComponentTransfer = new SchemaItemsComponentTransfer();
        if ($items->getOneOf()) {
            $schemaPropertyComponentTransfer->setOneOf($items->getOneOf());
        }

        $this->schemaItemsSpecificationComponent->setSchemaItemsComponentTransfer($schemaPropertyComponentTransfer);
        $schemaItemsSpecificationData = $this->schemaItemsSpecificationComponent->getSpecificationComponentData();

        if ($schemaItemsSpecificationData) {
            $schemaComponent->setItems($schemaItemsSpecificationData);
        }
    }
}
