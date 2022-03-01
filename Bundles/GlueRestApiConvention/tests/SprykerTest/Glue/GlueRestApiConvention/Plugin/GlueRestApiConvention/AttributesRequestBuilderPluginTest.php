<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\AttributesRequestBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group AttributesRequestBuilderPluginTest
 * Add your own group annotations below this line
 */
class AttributesRequestBuilderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @var int
     */
    protected const ATTRIBUTES_FIRST_FIELD = 100;

    /**
     * @var string
     */
    protected const ATTRIBUTES_SECOND_FIELD = 'array_key';

    /**
     * @var string
     */
    protected const ATTRIBUTES_SECOND_KEY = 'array_value';

    /**
     * @return void
     */
    public function testJsonApiResponseFormatterPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setContent($this->getContentData());

        //Act
        $attributesRequestBuilderPlugin = new AttributesRequestBuilderPlugin();
        $attributesRequestBuilderPlugin->build($glueRequestTransfer);

        //Assert
        $attributes = $glueRequestTransfer->getAttributes();
        $this->assertNotEmpty($attributes);
        $this->assertSame(static::ATTRIBUTES_FIRST_FIELD, $attributes[0]);
        $this->assertArrayHasKey(static::ATTRIBUTES_SECOND_KEY, $attributes);
        $this->assertSame(static::ATTRIBUTES_SECOND_FIELD, $attributes[static::ATTRIBUTES_SECOND_KEY]);
    }

    /**
     * @return string
     */
    protected function getContentData(): string
    {
        return json_encode([
            'data' => [
                static::ATTRIBUTES_FIRST_FIELD,
                static::ATTRIBUTES_SECOND_KEY => static::ATTRIBUTES_SECOND_FIELD,
            ],
        ]);
    }
}
