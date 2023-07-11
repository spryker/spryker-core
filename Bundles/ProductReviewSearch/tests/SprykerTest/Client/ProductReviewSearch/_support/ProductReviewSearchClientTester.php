<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductReviewSearch;

use Codeception\Actor;
use Codeception\Stub;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductReviewSearchClientTester extends Actor
{
    use _generated\ProductReviewSearchClientTesterActions;

    /**
     * @param \Elastica\Query\AbstractQuery $abstractQuery
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createQueryMock(AbstractQuery $abstractQuery): QueryInterface
    {
        $queryMock = Stub::makeEmpty(QueryInterface::class);

        $queryMock->method('getSearchQuery')->willReturn(new Query($abstractQuery));

        return $queryMock;
    }
}
