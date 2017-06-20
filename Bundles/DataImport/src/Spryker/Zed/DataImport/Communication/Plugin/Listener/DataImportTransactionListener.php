<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Plugin\Listener;

use Generated\Shared\Transfer\AfterDataSetImporterEventTransfer;
use Generated\Shared\Transfer\BeforeDataSetImporterEventTransfer;
use Propel\Runtime\Propel;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacade getFacade()
 * @method \Spryker\Zed\DataImport\Communication\DataImportCommunicationFactory getFactory()
 */
class DataImportTransactionListener extends AbstractPlugin implements EventListenerInterface
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function handle(TransferInterface $eventTransfer)
    {
        if ($eventTransfer instanceof BeforeDataSetImporterEventTransfer) {
            Propel::getConnection()->beginTransaction();
        }

        if ($eventTransfer instanceof AfterDataSetImporterEventTransfer) {
            if (!$eventTransfer->getIsSuccess()) {
                Propel::getConnection()->rollBack();

                return;
            }
            Propel::getConnection()->commit();
        }
    }

}
