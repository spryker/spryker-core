<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;

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
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function createReclamation(ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer): ReclamationTransfer
    {
        return $this->salesReclamationFacade->createReclamation($reclamationCreateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapOrderTransferToReclamationTransfer(
        OrderTransfer $orderTransfer,
        ReclamationTransfer $reclamationTransfer
    ): ReclamationTransfer {
        return $this->salesReclamationFacade->mapOrderTransferToReclamationTransfer($orderTransfer, $reclamationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function getReclamationById(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        return $this->salesReclamationFacade->getReclamationById($reclamationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function closeReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        return $this->salesReclamationFacade->closeReclamation($reclamationTransfer);
    }
}
