<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use ArrayObject;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
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
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function getReclamationById(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        $reclamationTransfer = $this->salesReclamationRepository->findReclamationById($reclamationTransfer);

        if (!$reclamationTransfer) {
            new ReclamationTransfer();
        }

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function getReclamationItemById(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer
    {
        $reclamationItemTransfer = $this->salesReclamationRepository->findReclamationItemById($reclamationItemTransfer);

        if (!$reclamationItemTransfer) {
            return new ReclamationItemTransfer();
        }

        return $reclamationItemTransfer;
    }
}
