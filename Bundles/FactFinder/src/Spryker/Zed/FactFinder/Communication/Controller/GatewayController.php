<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Communication\Controller;

use Generated\Shared\Transfer\FactFinderCsvTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\FactFinder\Business\FactFinderFacade getFacade()
 * @method \Spryker\Zed\FactFinder\Communication\FactFinderCommunicationFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\FactFinderCsvTransfer $csvTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderCsvTransfer
     */
    public function getFactFinderCsvAction(FactFinderCsvTransfer $csvTransfer)
    {
        return $csvTransfer->setContents(
            $this->getFacade()->getFactFinderCsv(
                $csvTransfer->getLocale(),
                $csvTransfer->getType(),
                $csvTransfer->getNumber()
            )
        );
    }

}
