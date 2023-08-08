<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ServicePointSearch;

use Codeception\Actor;
use Codeception\Stub;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Client\ServicePointSearch\PHPMD)
 */
class ServicePointSearchClientTester extends Actor
{
    use _generated\ServicePointSearchClientTesterActions;

    /**
     * @param \Elastica\Query\AbstractQuery|null $abstractQuery
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createQueryMock(?AbstractQuery $abstractQuery = null): QueryInterface
    {
        return Stub::makeEmpty(QueryInterface::class, [
            'getSearchQuery' => $this->createSearchQuery($abstractQuery),
        ]);
    }

    /**
     * @param \Elastica\Query\AbstractQuery|null $abstractQuery
     *
     * @return \Elastica\Query
     */
    public function createSearchQuery(?AbstractQuery $abstractQuery = null): Query
    {
        return new Query($abstractQuery);
    }
}
