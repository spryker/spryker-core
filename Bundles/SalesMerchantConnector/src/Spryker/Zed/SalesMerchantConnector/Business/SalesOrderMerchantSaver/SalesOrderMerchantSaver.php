<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business\SalesOrderMerchantSaver;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantFacadeInterface;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantProductOfferFacadeInterface;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToStoreFacadeInterface;
use Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorEntityManagerInterface;

class SalesOrderMerchantSaver implements SalesOrderMerchantSaverInterface
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrderMerchants(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $usedOfferReferences = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $offerTransfer = $itemTransfer->getOffer();
            if (!$offerTransfer || in_array($offerTransfer->getOfferReference(), $usedOfferReferences)) {
                //TODO: Should be removed when MP-1301 is done.
                //continue;
                if (in_array('offer1', $usedOfferReferences)) {
                    continue;
                }
                $offerTransfer = new OfferTransfer();
                $offerTransfer->setOfferReference('offer1');
                $itemTransfer->setOffer($offerTransfer);
            }

            $salesOrderMerchantTransfer = $this->createSalesOrderMerchantTransfer($offerTransfer, $saveOrderTransfer);
            if (!$salesOrderMerchantTransfer) {
                continue;
            }

            $usedOfferReferences[] = $offerTransfer->getOfferReference();

            $this->salesMerchantConnectorEntityManager->createSalesOrderMerchant($salesOrderMerchantTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer|null
     */
    protected function createSalesOrderMerchantTransfer(
        OfferTransfer $offerTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): ?SalesOrderMerchantTransfer {
        $merchantOfferReference = $offerTransfer->getOfferReference();
        if (!$merchantOfferReference) {
            return null;
        }

        $merchantProductOfferTransfer = $this->merchantProductOfferFacade->findMerchantByProductOfferReference($merchantOfferReference);

        $fkMerchant = $merchantProductOfferTransfer->getFkMerchant();
        if (!$fkMerchant) {
            return null;
        }

        $merchantTransfer = $this->merchantFacade->findOne((new MerchantCriteriaFilterTransfer())->setIdMerchant($fkMerchant));
        if (!$merchantTransfer) {
            return null;
        }

        $fkSalesOrder = $saveOrderTransfer->getIdSalesOrder();

        $salesOrderMerchantTransfer = new SalesOrderMerchantTransfer();
        $salesOrderMerchantTransfer->setMerchantReference($merchantTransfer->getMerchantKey());
        $salesOrderMerchantTransfer->setFkSalesOrder($fkSalesOrder);
        $salesOrderMerchantTransfer->setSalesOrderMerchantReference($this->generateSalesOrderMerchantReference($fkSalesOrder, $fkMerchant));

        return $salesOrderMerchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantTransfer $salesOrderMerchantTransfer
     * @param int $fkMerchant
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer
     */
    protected function addSalesOrderMerchantReference(
        SalesOrderMerchantTransfer $salesOrderMerchantTransfer,
        int $fkMerchant
    ): SalesOrderMerchantTransfer {
        $salesOrderMerchantReference = $this->generateSalesOrderMerchantReference($salesOrderMerchantTransfer->getFkSalesOrder(), $fkMerchant);

        return $salesOrderMerchantTransfer->setSalesOrderMerchantReference($salesOrderMerchantReference);
    }

    /**
     * @param int $fkSalesOrder
     * @param int $fkMerchant
     *
     * @return string
     */
    protected function generateSalesOrderMerchantReference(int $fkSalesOrder, int $fkMerchant): string
    {
        return sprintf('%s--%s--%s', $this->storeFacade->getCurrentStore()->getName(), $fkSalesOrder, $fkMerchant);
    }
}
