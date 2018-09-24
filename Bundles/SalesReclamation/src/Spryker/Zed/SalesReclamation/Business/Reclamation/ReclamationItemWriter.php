<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use Generated\Shared\Transfer\ReclamationItemTransfer;
use Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface;

class ReclamationItemWriter implements ReclamationItemWriterInterface
{
    /**
     * @var \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface
     */
    protected $salesReclamationEntityManager;

    /**
     * @param \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface $salesReclamationEntityManager
     */
    public function __construct(
        SalesReclamationEntityManagerInterface $salesReclamationEntityManager
    ) {
        $this->salesReclamationEntityManager = $salesReclamationEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function updateReclamationItem(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer
    {
        return $this->salesReclamationEntityManager->saveReclamationItem($reclamationItemTransfer);
    }
}
