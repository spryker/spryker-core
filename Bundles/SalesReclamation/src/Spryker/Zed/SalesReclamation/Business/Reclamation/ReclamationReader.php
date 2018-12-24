<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\SalesReclamation\Business\Exception\ReclamationItemNotFoundException;
use Spryker\Zed\SalesReclamation\Business\Exception\ReclamationNotFoundException;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface;

class ReclamationReader implements ReclamationReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface
     */
    protected $salesReclamationRepository;

    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface $salesReclamationRepository
     */
    public function __construct(
        SalesReclamationRepositoryInterface $salesReclamationRepository
    ) {
        $this->salesReclamationRepository = $salesReclamationRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @throws \Spryker\Zed\SalesReclamation\Business\Exception\ReclamationNotFoundException

     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function getReclamationById(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        $idSalesReclamation = $reclamationTransfer->getIdSalesReclamation();
        $reclamationTransfer = $this->salesReclamationRepository->findReclamationById($reclamationTransfer);

        if (!$reclamationTransfer) {
            throw new ReclamationNotFoundException(
                sprintf('There is no reclamation with id %s', $idSalesReclamation)
            );
        }

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @throws \Spryker\Zed\SalesReclamation\Business\Exception\ReclamationItemNotFoundException
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function getReclamationItemById(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer
    {
        $idSalesReclamationItem = $reclamationItemTransfer->getIdSalesReclamationItem();
        $reclamationItemTransfer = $this->salesReclamationRepository->findReclamationItemById($reclamationItemTransfer);

        if (!$reclamationItemTransfer) {
            throw new ReclamationItemNotFoundException(
                sprintf('There is no reclamation item with id %s', $idSalesReclamationItem)
            );
        }

        return $reclamationItemTransfer;
    }
}
