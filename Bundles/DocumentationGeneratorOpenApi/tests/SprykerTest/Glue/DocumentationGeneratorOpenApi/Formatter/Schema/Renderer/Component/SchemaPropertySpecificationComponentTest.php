<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SchemaPropertyComponentTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component\SchemaPropertySpecificationComponent;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DocumentationGeneratorOpenApi
 * @group Formatter
 * @group Schema
 * @group Renderer
 * @group Component
 * @group SchemaPropertySpecificationComponentTest
 * Add your own group annotations below this line
 */
class SchemaPropertySpecificationComponentTest extends Unit
{
    /**
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_ITEMS = 'items';

    /**
     * @var string
     */
    protected const VALUE_TYPE_ARRAY = 'array';

    /**
     * @return void
     */
    public function testGetSpecificationComponentDataSetsKeyItemsAsEmptyArray(): void
    {
        //Arrange
        $schemaPropertySpecificationComponent = new SchemaPropertySpecificationComponent();
        $schemaPropertyComponentTransfer = new SchemaPropertyComponentTransfer();
        $schemaPropertyComponentTransfer->setName(static::KEY_NAME);
        $schemaPropertyComponentTransfer->setType(static::VALUE_TYPE_ARRAY);

        //Act
        $data = $schemaPropertySpecificationComponent->getSpecificationComponentData($schemaPropertyComponentTransfer);

        //Assert
        $this->assertSame([], $data[static::KEY_NAME][static::KEY_ITEMS]);
    }
}
