<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business;

use Psr\Log\LoggerInterface;

interface TransferFacadeInterface
{

    /**
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateTransferObjects(LoggerInterface $messenger);

    /**
     * @api
     *
     * @return void
     */
    public function deleteGeneratedTransferObjects();

}
