<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use ArrayObject;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface;

class ReclamationReader implements ReclamationReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface
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
     * @return \ArrayObject|null
     */
    public function findReclamations(): ?ArrayObject
    {
        return $this->salesReclamationRepository->findReclamations();
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    public function findReclamationById(ReclamationTransfer $reclamationTransfer): ?ReclamationTransfer
    {
        return $this->salesReclamationRepository->findReclamationById($reclamationTransfer);
    }
}
