<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculator;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculatorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group QuoteApproval
 * @group Business
 * @group QuoteStatusCalculatorTest
 * Add your own group annotations below this line
 */
class QuoteStatusCalculatorTest extends Unit
{
    /**
     * @return void
     */
    public function testCalculateQuoteStatusWithOneWaitingShouldReturnWaiting(): void
    {
        $statuses = [
            QuoteApprovalConfig::STATUS_WAITING,
        ];

        $quoteTransfer = $this->createQuoteTransfer($statuses);
        $result = $this->createQuoteStatusCalculator()->calculateQuoteStatus($quoteTransfer);

        $this->assertSame($result, QuoteApprovalConfig::STATUS_WAITING);
    }

    /**
     * @return void
     */
    public function testCalculateQuoteStatusWithApprovedShouldReturnApproved(): void
    {
        $statuses = [
            QuoteApprovalConfig::STATUS_APPROVED,
        ];

        $quoteTransfer = $this->createQuoteTransfer($statuses);
        $result = $this->createQuoteStatusCalculator()->calculateQuoteStatus($quoteTransfer);

        $this->assertSame($result, QuoteApprovalConfig::STATUS_APPROVED);
    }

    /**
     * @return void
     */
    public function testCalculateQuoteStatusWithDeclinedShouldReturnDeclined(): void
    {
        $statuses = [
            QuoteApprovalConfig::STATUS_DECLINED,
        ];

        $quoteTransfer = $this->createQuoteTransfer($statuses);
        $result = $this->createQuoteStatusCalculator()->calculateQuoteStatus($quoteTransfer);

        $this->assertSame($result, QuoteApprovalConfig::STATUS_DECLINED);
    }

    /**
     * @return void
     */
    public function testCalculateQuoteStatusWithWaitingAndApprovedShouldReturnApproved(): void
    {
        $statuses = [
            QuoteApprovalConfig::STATUS_WAITING,
            QuoteApprovalConfig::STATUS_APPROVED,
        ];

        $quoteTransfer = $this->createQuoteTransfer($statuses);
        $result = $this->createQuoteStatusCalculator()->calculateQuoteStatus($quoteTransfer);

        $this->assertSame($result, QuoteApprovalConfig::STATUS_APPROVED);
    }

    /**
     * @return void
     */
    public function testCalculateQuoteStatusWithWaitingAndDeclinedShouldReturnWaiting(): void
    {
        $statuses = [
            QuoteApprovalConfig::STATUS_WAITING,
            QuoteApprovalConfig::STATUS_DECLINED,
        ];

        $quoteTransfer = $this->createQuoteTransfer($statuses);
        $result = $this->createQuoteStatusCalculator()->calculateQuoteStatus($quoteTransfer);

        $this->assertSame($result, QuoteApprovalConfig::STATUS_WAITING);
    }

    /**
     * @return void
     */
    public function testCalculateQuoteStatusWithWaitingDeclinedAndApprovedShouldReturnApproved(): void
    {
        $statuses = [
            QuoteApprovalConfig::STATUS_WAITING,
            QuoteApprovalConfig::STATUS_DECLINED,
            QuoteApprovalConfig::STATUS_APPROVED,
        ];

        $quoteTransfer = $this->createQuoteTransfer($statuses);
        $result = $this->createQuoteStatusCalculator()->calculateQuoteStatus($quoteTransfer);

        $this->assertSame($result, QuoteApprovalConfig::STATUS_APPROVED);
    }

    /**
     * @return void
     */
    public function testCalculateQuoteStatusWithEmptyDataShouldReturnNull(): void
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $result = $this->createQuoteStatusCalculator()->calculateQuoteStatus($quoteTransfer);

        $this->assertNull($result);
    }

    /**
     * @param string[] $statuses
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(array $statuses = []): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setQuoteApprovals($this->createQuoteApprovalTransfers($statuses));

        return $quoteTransfer;
    }

    /**
     * @param string[] $statuses
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\QuoteApprovalTransfer[]
     */
    protected function createQuoteApprovalTransfers(array $statuses): ArrayObject
    {
        $quoteApprovalTransfers = [];

        foreach ($statuses as $status) {
            $quoteApprovalTransfers[] = (new QuoteApprovalTransfer())->setStatus($status);
        }

        return new ArrayObject($quoteApprovalTransfers);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculatorInterface
     */
    protected function createQuoteStatusCalculator(): QuoteStatusCalculatorInterface
    {
        return new QuoteStatusCalculator();
    }
}
