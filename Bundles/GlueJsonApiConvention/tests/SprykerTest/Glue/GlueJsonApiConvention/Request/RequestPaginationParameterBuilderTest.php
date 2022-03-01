<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestPaginationParameterBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Request
 * @group RequestPaginationParameterBuilderTest
 * Add your own group annotations below this line
 */
class RequestPaginationParameterBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const QUERY_PAGINATION = 'page';

    /**
     * @var string
     */
    protected const PAGINATION_OFFSET = 'offset';

    /**
     * @var string
     */
    protected const PAGINATION_LIMIT = 'limit';

    /**
     * @return void
     */
    public function testNoPagination(): void
    {
        //Arrange
        $glueRequest = new GlueRequestTransfer();

        //Act
        $builder = new RequestPaginationParameterBuilder();
        $result = $builder->extract($glueRequest);

        //Assert
        $this->assertNull($result->getPagination());
    }

    /**
     * @return void
     */
    public function testEmptyPagination(): void
    {
        //Act
        $result = $this->buildRequest(null, null);

        //Assert
        $this->assertNull($result->getPagination());
    }

    /**
     * @return void
     */
    public function testMissingOffset(): void
    {
        //Act
        $result = $this->buildRequest(null, 10);

        //Assert
        $this->assertNull($result->getPagination());
    }

    /**
     * @return void
     */
    public function testMissingLimit(): void
    {
        //Act
        $result = $this->buildRequest(0, null);

        //Assert
        $this->assertNull($result->getPagination());
    }

    /**
     * @return void
     */
    public function testCompletePage(): void
    {
        //Act
        $result = $this->buildRequest(0, 10);

        //Assert
        $this->assertInstanceOf(PaginationTransfer::class, $result->getPagination());
        $this->assertSame(0, $result->getPagination()->getOffset());
        $this->assertSame(10, $result->getPagination()->getLimit());
    }

    /**
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function buildRequest(?int $offset = null, ?int $limit = null): GlueRequestTransfer
    {
        $glueRequest = new GlueRequestTransfer();
        $glueRequest->setQueryFields([
            static::QUERY_PAGINATION => [
                static::PAGINATION_OFFSET => $offset,
                static::PAGINATION_LIMIT => $limit,
            ],
        ]);

        $builder = new RequestPaginationParameterBuilder();

        return $builder->extract($glueRequest);
    }
}
