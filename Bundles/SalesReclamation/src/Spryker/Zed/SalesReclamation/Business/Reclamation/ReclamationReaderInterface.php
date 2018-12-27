<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use Generated\Shared\Transfer\ReclamationTransfer;

interface ReclamationReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @throws \Spryker\Zed\SalesReclamation\Business\Exception\ReclamationNotFoundException
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function getReclamationById(ReclamationTransfer $reclamationTransfer): ReclamationTransfer;
}
