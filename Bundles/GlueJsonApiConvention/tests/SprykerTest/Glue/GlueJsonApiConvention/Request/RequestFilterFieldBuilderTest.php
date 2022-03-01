<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueFilterTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestFilterFieldBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Request
 * @group RequestFilterFieldBuilderTest
 * Add your own group annotations below this line
 */
class RequestFilterFieldBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const QUERY_FILTER = 'filter';

    /**
     * @return void
     */
    public function testEmptyFilter(): void
    {
        //Act
        $glueRequestTransfer = $this->buildRequest([]);

        //Assert
        $this->assertCount(0, $glueRequestTransfer->getFilters());
    }

    /**
     * @return void
     */
    public function testExtractReturnsGlueFilterTransfer(): void
    {
        //Act
        $glueRequestTransfer = $this->buildRequest(['items.name' => 'item name']);

        //Assert
        $this->assertCount(1, $glueRequestTransfer->getFilters());
        $this->assertInstanceOf(GlueFilterTransfer::class, $glueRequestTransfer->getFilters()->offsetGet(0));
        $this->assertSame('name', $glueRequestTransfer->getFilters()->offsetGet(0)->getField());
        $this->assertSame('item name', $glueRequestTransfer->getFilters()->offsetGet(0)->getValue());
        $this->assertSame('items', $glueRequestTransfer->getFilters()->offsetGet(0)->getResource());
    }

    /**
     * @param array $filter
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function buildRequest(array $filter = []): GlueRequestTransfer
    {
        //Arrange
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setQueryFields([static::QUERY_FILTER => $filter]);

        //Act
        $builder = new RequestFilterFieldBuilder();

        return $builder->extract($glueRequest);
    }
}
