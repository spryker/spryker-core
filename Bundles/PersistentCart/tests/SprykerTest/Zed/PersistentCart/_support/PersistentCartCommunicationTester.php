<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCart;

use Codeception\Actor;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class PersistentCartCommunicationTester extends Actor
{
    use _generated\PersistentCartCommunicationTesterActions;

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteFromPersistenceByIdQuote(int $idQuote): QuoteTransfer
    {
        $quoteEntity = $this->getQuoteQuery()
            ->filterByIdQuote($idQuote)
            ->findOne();

        return (new QuoteTransfer())->fromArray($quoteEntity->toArray(), true);
    }

    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    protected function getQuoteQuery(): SpyQuoteQuery
    {
        return SpyQuoteQuery::create();
    }
}
