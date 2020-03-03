<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business\Writer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorEntityManagerInterface;
use Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorRepositoryInterface;

class OrderWriter implements OrderWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorRepositoryInterface
     */
    protected $companySalesConnectorRepository;

    /**
     * @var \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorEntityManagerInterface
     */
    protected $companySalesConnectorEntityManager;

    /**
     * @param \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorRepositoryInterface $companySalesConnectorRepository
     * @param \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorEntityManagerInterface $companySalesConnectorEntityManager
     */
    public function __construct(
        CompanySalesConnectorRepositoryInterface $companySalesConnectorRepository,
        CompanySalesConnectorEntityManagerInterface $companySalesConnectorEntityManager
    ) {
        $this->companySalesConnectorRepository = $companySalesConnectorRepository;
        $this->companySalesConnectorEntityManager = $companySalesConnectorEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function updateOrderCompanyUuid(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): void
    {
        $saveOrderTransfer->requireIdSalesOrder();

        $companyUuid = $this->extractCompanyUuidFromQuote($quoteTransfer);

        if (!$companyUuid) {
            return;
        }

        $orderTransfer = $this->companySalesConnectorRepository->findOrderByIdSalesOrder(
            $saveOrderTransfer->getIdSalesOrder()
        );

        if (!$orderTransfer) {
            return;
        }

        $orderTransfer->setCompanyUuid($companyUuid);

        $this->companySalesConnectorEntityManager->updateOrder($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function extractCompanyUuidFromQuote(QuoteTransfer $quoteTransfer): ?string
    {
        if (!$quoteTransfer->getCustomer()) {
            return null;
        }

        $companyUserTransfer = $quoteTransfer->getCustomer()->getCompanyUserTransfer();

        if (!$companyUserTransfer || !$companyUserTransfer->getCompany()) {
            return null;
        }

        return $companyUserTransfer->getCompany()->getUuid();
    }
}
