<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\MultiCart;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;

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
 * @method \Spryker\Client\MultiCart\MultiCartClientInterface getClient(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class MultiCartClientTester extends Actor
{
    use _generated\MultiCartClientTesterActions;

    /**
     * @uses \Spryker\Client\MultiCart\Storage\MultiCartStorage::SESSION_KEY_QUOTE_COLLECTION
     *
     * @var string
     */
    public const SESSION_KEY_QUOTE_COLLECTION = 'SESSION_KEY_QUOTE_COLLECTION';

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function haveQuote(array $override = []): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withStore($override)
            ->withItem($override)
            ->withCustomer()
            ->withTotals()
            ->build();
    }
}
