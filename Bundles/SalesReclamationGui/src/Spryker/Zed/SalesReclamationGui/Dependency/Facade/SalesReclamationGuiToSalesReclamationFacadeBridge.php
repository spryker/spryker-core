<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

class SalesReclamationGuiToSalesReclamationFacadeBridge implements SalesReclamationGuiToSalesReclamationFacadeInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Business\SalesReclamationFacadeInterface
     */
    protected $salesReclamationFacade;

    /**
     * @param \Spryker\Zed\SalesReclamation\Business\SalesReclamationFacadeInterface $salesReclamationFacade
     */
    public function __construct($salesReclamationFacade)
    {
        $this->salesReclamationFacade = $salesReclamationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    public function createReclamation(ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer): ?ReclamationTransfer
    {
        return $this->salesReclamationFacade->createReclamation($reclamationCreateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function hydrateReclamationByOrder(OrderTransfer $orderTransfer): ReclamationTransfer
    {
        return $this->salesReclamationFacade->hydrateReclamationByOrder($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    public function hydrateReclamationByIdReclamation(ReclamationTransfer $reclamationTransfer): ?ReclamationTransfer
    {
        return $this->salesReclamationFacade->hydrateReclamationByIdReclamation($reclamationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderReclamation(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->salesReclamationFacade->saveOrderReclamation($quoteTransfer, $saveOrderTransfer);
    }
}
