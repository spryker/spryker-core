<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\FileManagerStorage\Communication\FileManagerStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileManagerStorage\Business\FileManagerStorageFacadeInterface getFacade()
 */
abstract class AbstractFileManagerListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param int[] $fileIds
     *
     * @return void
     */
    protected function publish($fileIds)
    {
        $this->getFacade()->publishFile($fileIds);
    }

    /**
     * @param int[] $fileIds
     *
     * @return void
     */
    protected function unpublish($fileIds)
    {
        $this->getFacade()->unpublishFile($fileIds);
    }
}
