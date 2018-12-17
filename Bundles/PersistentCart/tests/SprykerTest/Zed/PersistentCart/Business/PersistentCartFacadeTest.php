<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCart\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Quote\Persistence\SpyQuote;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PersistentCart
 * @group Business
 * @group Facade
 * @group PersistentCartFacadeTest
 * Add your own group annotations below this line
 */
class PersistentCartFacadeTest extends Unit
{
    protected const QUOTE_DATA_KEY_IS_LOCKED = 'isLocked';

    /**
     * @var \SprykerTest\Zed\PersistentCart\PersistentCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testQuoteLock()
    {
        $quoteTransfer = $this->prepareQuoteTransferForLockTest(true);

        /**
         * @var \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface $persistentCartFacade
         */
        $persistentCartFacade = $this->tester->getFacade();

        $quoteResponseTransfer = $persistentCartFacade->lockQuote($quoteTransfer);

        $quoteDataArray = $this->getQuoteDataByIdQuote($quoteTransfer->getIdQuote());

        $this->assertTrue($quoteResponseTransfer->getQuoteTransfer()->getIsLocked());
        $this->assertContains(static::QUOTE_DATA_KEY_IS_LOCKED, $quoteDataArray);
        $this->assertTrue($quoteDataArray[static::QUOTE_DATA_KEY_IS_LOCKED]);
    }

    /**
     * @return void
     */
    public function testQuoteUnlock()
    {
        $quoteTransfer = $this->prepareQuoteTransferForLockTest(true);

        /**
         * @var \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface $persistentCartFacade
         */
        $persistentCartFacade = $this->tester->getFacade();

        $quoteResponseTransfer = $persistentCartFacade->lockQuote($quoteTransfer);

        $quoteDataArray = $this->getQuoteDataByIdQuote($quoteTransfer->getIdQuote());

        $this->assertTrue($quoteResponseTransfer->getQuoteTransfer()->getIsLocked());
        $this->assertContains(static::QUOTE_DATA_KEY_IS_LOCKED, $quoteDataArray);
        $this->assertTrue($quoteDataArray[static::QUOTE_DATA_KEY_IS_LOCKED]);
    }

    /**
     * @param bool $isLocked
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteTransferForLockTest(bool $isLocked): QuoteTransfer
    {
        $quote = SpyQuoteQuery::create()->findOne();

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setIsLocked($isLocked);
        $quoteTransfer->setIdQuote($quote->getIdQuote());

        return $quoteTransfer;
    }

    /**
     * @param int $idQuote
     *
     * @return \Orm\Zed\Quote\Persistence\SpyQuote|null
     */
    protected function getQuoteById(int $idQuote): ?SpyQuote
    {
        return SpyQuoteQuery::create()->findOneByIdQuote($idQuote);
    }

    /**
     * @param int $idQuote
     *
     * @return array|null
     */
    protected function getQuoteDataByIdQuote(int $idQuote): ?array
    {
        $quoteEntity = $this->getQuoteById($idQuote);

        if (!$quoteEntity) {
            return null;
        }

        return json_decode($quoteEntity->getQuoteData(), true);
    }
}
