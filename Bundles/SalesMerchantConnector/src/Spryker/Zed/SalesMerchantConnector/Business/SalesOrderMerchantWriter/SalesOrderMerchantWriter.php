<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business\SalesOrderMerchantWriter;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantFacadeInterface;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantProductOfferFacadeInterface;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToStoreFacadeInterface;
use Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface;

class SalesOrderMerchantWriter implements SalesOrderMerchantWriterInterface
{
    /**
     * @var \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface
     */
    protected $salesMerchantConnectorEntityManager;

    /**
     * @var \Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantProductOfferFacadeInterface
     */
    protected $merchantProductOfferFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface $salesMerchantConnectorEntityManager
     * @param \Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantProductOfferFacadeInterface $merchantProductOfferFacade
     * @param \Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SalesMerchantConnectorEntityManagerInterface $salesMerchantConnectorEntityManager,
        SalesMerchantConnectorToMerchantProductOfferFacadeInterface $merchantProductOfferFacade,
        SalesMerchantConnectorToMerchantFacadeInterface $merchantFacade,
        SalesMerchantConnectorToStoreFacadeInterface $storeFacade
    ) {
        $this->salesMerchantConnectorEntityManager = $salesMerchantConnectorEntityManager;
        $this->merchantProductOfferFacade = $merchantProductOfferFacade;
        $this->merchantFacade = $merchantFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer|null
     */
    public function createSalesOrderMerchant(SalesOrderMerchantSaveTransfer $salesOrderMerchantSaveTransfer): ?SalesOrderMerchantTransfer
    {
        $salesOrderMerchantSaveTransfer->requireIdSalesOrder();
        $salesOrderMerchantSaveTransfer->requireOfferReference();

        $idSalesOrder = $salesOrderMerchantSaveTransfer->getIdSalesOrder();
        $idMerchant = $this->merchantProductOfferFacade->findIdMerchantByProductOfferReference($salesOrderMerchantSaveTransfer->getOfferReference());
        if (!$idMerchant) {
            return null;
        }

        $merchantTransfer = $this->merchantFacade->findOne((new MerchantCriteriaFilterTransfer())->setIdMerchant($idMerchant));

        return $this->salesMerchantConnectorEntityManager->createSalesOrderMerchant(
            $this->createSalesOrderMerchantTransfer($idSalesOrder, $idMerchant, $merchantTransfer->getMerchantKey())
        );
    }

    /**
     * @param int $idSalesOrder
     * @param int $idMerchant
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    protected function createSalesOrderMerchantTransfer(
        int $idSalesOrder,
        int $idMerchant,
        string $merchantReference
    ): SalesOrderMerchantTransfer {
        $salesOrderMerchantTransfer = new SalesOrderMerchantTransfer();
        $salesOrderMerchantTransfer->setMerchantReference($merchantReference);
        $salesOrderMerchantTransfer->setFkSalesOrder($idSalesOrder);
        $salesOrderMerchantTransfer->setSalesOrderMerchantReference($this->generateSalesOrderMerchantReference($idSalesOrder, $idMerchant));

        return $salesOrderMerchantTransfer;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idMerchant
     *
     * @return string
     */
    protected function generateSalesOrderMerchantReference(int $idSalesOrder, int $idMerchant): string
    {
        return sprintf('%s--%s--%s', $this->storeFacade->getCurrentStore()->getName(), $idSalesOrder, $idMerchant);
    }
}
