<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Plugin;

use Spryker\Zed\DataImport\Communication\Plugin\Listener\DataImportConsoleTimerListener;
use Spryker\Zed\DataImport\Dependency\DataImportEvents;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacade getFacade()
 * @method \Spryker\Zed\DataImport\Communication\DataImportCommunicationFactory getFactory()
 */
class DataImportTransactionEventSubscriberPlugin extends AbstractPlugin implements EventSubscriberInterface
{

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $dataImportConsoleTimerListener = new DataImportConsoleTimerListener();
        $eventCollection
            ->addListener(DataImportEvents::BEFORE_DATA_SET_IMPORTER, $dataImportConsoleTimerListener)
            ->addListener(DataImportEvents::AFTER_DATA_SET_IMPORTER, $dataImportConsoleTimerListener);

        return $eventCollection;
    }

}
