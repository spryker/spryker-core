<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestSortParameterBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Request
 * @group RequestSortParameterBuilderTest
 * Add your own group annotations below this line
 */
class RequestSortParameterBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const FIRST_FIELD_NAME = 'field1';

    /**
     * @var string
     */
    protected const SECOND_FIELD_NAME = 'field2';

    /**
     * @var string
     */
    protected const URL_WITH_SORT_PARAMETER = '/foo/bar?sort=';

    /**
     * @var string
     */
    protected const QUERY_SORT = 'sort';

    /**
     * @return void
     */
    public function testEmptySorting(): void
    {
        //Act
        $glueRequestTransfer = $this->buildRequest([]);

        //Assert
        $this->assertCount(0, $glueRequestTransfer->getSortings());
    }

    /**
     * @return void
     */
    public function testAscendingSortField(): void
    {
        //Act
        $glueRequestTransfer = $this->buildRequest([static::FIRST_FIELD_NAME]);

        //Assert
        $this->firstSortingAsserts($glueRequestTransfer, 1);
        $this->assertTrue($glueRequestTransfer->getSortings()->offsetGet(0)->getIsAscending());
    }

    /**
     * @return void
     */
    public function testDescendingSortField(): void
    {
        //Act
        $glueRequestTransfer = $this->buildRequest(['-' . static::FIRST_FIELD_NAME]);

        //Assert
        $this->firstSortingAsserts($glueRequestTransfer, 1);
        $this->assertFalse($glueRequestTransfer->getSortings()->offsetGet(0)->getIsAscending());
    }

    /**
     * @return void
     */
    public function testMultipleAscendingSortingFields(): void
    {
        //Act
        $glueRequestTransfer = $this->buildRequest([static::FIRST_FIELD_NAME, static::SECOND_FIELD_NAME]);

        //Assert
        $this->firstSortingAsserts($glueRequestTransfer, 2);
        $this->secondSortingAsserts($glueRequestTransfer);
        $this->assertTrue($glueRequestTransfer->getSortings()->offsetGet(0)->getIsAscending());
    }

    /**
     * @return void
     */
    public function testMultipleDescendingSortingFields(): void
    {
        //Act
        $glueRequestTransfer = $this->buildRequest(['-' . static::FIRST_FIELD_NAME, '-' . static::SECOND_FIELD_NAME]);

        //Assert
        $this->firstSortingAsserts($glueRequestTransfer, 2);
        $this->secondSortingAsserts($glueRequestTransfer);
        $this->assertFalse($glueRequestTransfer->getSortings()->offsetGet(0)->getIsAscending());
    }

    /**
     * @return void
     */
    public function testMultipleSortingFieldsWithDifferentDirections(): void
    {
        //Act
        $glueRequestTransfer = $this->buildRequest(['-' . static::FIRST_FIELD_NAME, static::SECOND_FIELD_NAME]);

        //Assert
        $this->firstSortingAsserts($glueRequestTransfer, 2);
        $this->secondSortingAsserts($glueRequestTransfer);
    }

    /**
     * @param array $sorting
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function buildRequest(array $sorting = []): GlueRequestTransfer
    {
        //Arrange
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setQueryFields([static::QUERY_SORT => implode(',', $sorting)]);

        //Act
        $builder = new RequestSortParameterBuilder();

        return $builder->extract($glueRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param int $expectedCount
     *
     * @return void
     */
    protected function firstSortingAsserts(GlueRequestTransfer $glueRequestTransfer, int $expectedCount): void
    {
        $sorting = $glueRequestTransfer->getSortings();
        $this->assertCount($expectedCount, $sorting);
        $firstSorting = $sorting->offsetGet(0);
        $this->assertInstanceOf(SortTransfer::class, $firstSorting);
        $this->assertSame(static::FIRST_FIELD_NAME, $firstSorting->getField());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    protected function secondSortingAsserts(GlueRequestTransfer $glueRequestTransfer): void
    {
        $secondSorting = $glueRequestTransfer->getSortings()->offsetGet(1);
        $this->assertInstanceOf(SortTransfer::class, $secondSorting);
        $this->assertSame(static::SECOND_FIELD_NAME, $secondSorting->getField());
    }
}
