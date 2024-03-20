<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search;

use Codeception\Actor;
use Codeception\Stub;
use Spryker\Client\Search\Dependency\Facade\SearchToLocaleClientInterface;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Client\Search\SearchClientInterface getClient()
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 * @SuppressWarnings(\SprykerTest\Client\Search\PHPMD)
 */
class SearchClientTester extends Actor
{
    use _generated\SearchClientTesterActions;

    /**
     * @var string
     */
    public const LOCALE = 'ab_CD';

    /**
     * @return \Spryker\Client\Search\Dependency\Facade\SearchToLocaleClientInterface
     */
    public function createLocaleClient(): SearchToLocaleClientInterface
    {
        return Stub::makeEmpty(SearchToLocaleClientInterface::class);
    }
}
