<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\QuoteApproval\Business\QuoteApprovalBusinessFactory getFactory()
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface getRepository()
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface getEntityManager()
 */
class QuoteApprovalFacade extends AbstractFacade implements QuoteApprovalFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFactory()->createQuoteApprovalCreator()->createQuoteApproval($quoteApprovalCreateRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteApprovalRemover()
            ->removeQuoteApproval($quoteApprovalRemoveRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getQuoteApproversList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        return $this->getFactory()->createQuoteApproversProvider()->getApproversList($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer[]
     */
    public function getQuoteApprovalsByIdQuote(int $idQuote): array
    {
        return $this->getFactory()->getQuoteApprovalRepository()->findQuoteApprovalCollectionByIdQuote($idQuote);
    }
}
