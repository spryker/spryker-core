<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Writer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorEntityManagerInterface;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorRepositoryInterface;

class OrderWriter implements OrderWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorRepositoryInterface
     */
    protected $companyBusinessUnitSalesConnectorRepository;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorEntityManagerInterface
     */
    protected $companyBusinessUnitSalesConnectorEntityManager;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorRepositoryInterface $companyBusinessUnitSalesConnectorRepository
     * @param \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorEntityManagerInterface $companyBusinessUnitSalesConnectorEntityManager
     */
    public function __construct(
        CompanyBusinessUnitSalesConnectorRepositoryInterface $companyBusinessUnitSalesConnectorRepository,
        CompanyBusinessUnitSalesConnectorEntityManagerInterface $companyBusinessUnitSalesConnectorEntityManager
    ) {
        $this->companyBusinessUnitSalesConnectorRepository = $companyBusinessUnitSalesConnectorRepository;
        $this->companyBusinessUnitSalesConnectorEntityManager = $companyBusinessUnitSalesConnectorEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function updateOrderCompanyBusinessUnitUuid(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $saveOrderTransfer->requireIdSalesOrder();

        $companyBusinessUnitUuid = $this->extractCompanyBusinessUnitUuidFromQuote($quoteTransfer);

        if (!$companyBusinessUnitUuid) {
            return;
        }

        $orderTransfer = $this->companyBusinessUnitSalesConnectorRepository->findOrderByIdSalesOrder(
            $saveOrderTransfer->getIdSalesOrder()
        );

        if (!$orderTransfer) {
            return;
        }

        $orderTransfer->setCompanyBusinessUnitUuid($companyBusinessUnitUuid);

        $this->companyBusinessUnitSalesConnectorEntityManager->updateOrder($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function extractCompanyBusinessUnitUuidFromQuote(QuoteTransfer $quoteTransfer): ?string
    {
        if (!$quoteTransfer->getCustomer()) {
            return null;
        }

        $companyUserTransfer = $quoteTransfer->getCustomer()->getCompanyUserTransfer();

        if (!$companyUserTransfer || !$companyUserTransfer->getCompanyBusinessUnit()) {
            return null;
        }

        return $companyUserTransfer->getCompanyBusinessUnit()->getUuid();
    }
}
