<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface;

class QuoteApprovalWriter implements QuoteApprovalWriterInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalValidatorInterface
     */
    protected $quoteApprovalValidator;

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalMessageBuilderInterface
     */
    protected $quoteApprovalMessageBuilder;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface
     */
    protected $quoteApprovalEntityManager;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface
     */
    protected $quoteApprovalRepository;

    /**
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalValidatorInterface $quoteApprovalValidator
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalMessageBuilderInterface $quoteApprovalMessageBuilder
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface $quoteApprovalRepository
     */
    public function __construct(
        QuoteApprovalValidatorInterface $quoteApprovalValidator,
        QuoteApprovalMessageBuilderInterface $quoteApprovalMessageBuilder,
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager,
        QuoteApprovalRepositoryInterface $quoteApprovalRepository
    ) {
        $this->quoteApprovalValidator = $quoteApprovalValidator;
        $this->quoteApprovalMessageBuilder = $quoteApprovalMessageBuilder;
        $this->quoteApprovalEntityManager = $quoteApprovalEntityManager;
        $this->quoteApprovalRepository = $quoteApprovalRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->updateQuoteWithStatus($quoteApprovalRequestTransfer, QuoteApprovalConfig::STATUS_APPROVED);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function declineQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->updateQuoteWithStatus($quoteApprovalRequestTransfer, QuoteApprovalConfig::STATUS_DECLINED);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     * @param string $status
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function updateQuoteWithStatus(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer, string $status): QuoteApprovalResponseTransfer
    {
        $quoteApprovalResponseTransfer = (new QuoteApprovalResponseTransfer())
            ->setIsSuccessful(false);

        if (!$quoteApprovalRequestTransfer->getIdQuoteApproval()) {
            return $quoteApprovalResponseTransfer;
        }

        $quoteApprovalTransfer = $this->quoteApprovalRepository
            ->findQuoteApprovalById($quoteApprovalRequestTransfer->getIdQuoteApproval());

        if ($quoteApprovalTransfer === null) {
            return $quoteApprovalResponseTransfer;
        }

        if (!$this->quoteApprovalValidator->canUpdateQuoteApprovalRequest($quoteApprovalRequestTransfer, $quoteApprovalTransfer)) {
            return $quoteApprovalResponseTransfer;
        }

        $quoteApprovalResponseTransfer = $this->updateQuoteApprovalWithStatus($quoteApprovalTransfer, $status);

        return $quoteApprovalResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param string $status
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function updateQuoteApprovalWithStatus(QuoteApprovalTransfer $quoteApprovalTransfer, string $status): QuoteApprovalResponseTransfer
    {
        $quoteApprovalTransfer->setStatus($status);
        $quoteApprovalTransfer = $this->quoteApprovalEntityManager->updateQuoteApproval($quoteApprovalTransfer);

        return (new QuoteApprovalResponseTransfer())
            ->setQuoteApproval($quoteApprovalTransfer)
            ->setIsSuccessful(true)
            ->setMessage($this->quoteApprovalMessageBuilder->getSuccessMessage($quoteApprovalTransfer, $status));
    }
}
