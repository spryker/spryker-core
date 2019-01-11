<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface;

class QuoteApprovalRemover implements QuoteApprovalRemoverInterface
{
    protected const STATUS_NAME = 'canceled';

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
    public function cancelQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer
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

        if (!$this->quoteApprovalValidator->canDeleteQuoteApprovalRequest($quoteApprovalRequestTransfer, $quoteApprovalTransfer)) {
            return $quoteApprovalResponseTransfer;
        }

        $this->quoteApprovalEntityManager->deleteQuoteApprovalById($quoteApprovalRequestTransfer->getIdQuoteApproval());

        $quoteApprovalResponseTransfer->setIsSuccessful(true)
            ->setMessage($this->quoteApprovalMessageBuilder->getSuccessMessage($quoteApprovalTransfer, self::STATUS_NAME));

        return $quoteApprovalResponseTransfer;
    }
}
