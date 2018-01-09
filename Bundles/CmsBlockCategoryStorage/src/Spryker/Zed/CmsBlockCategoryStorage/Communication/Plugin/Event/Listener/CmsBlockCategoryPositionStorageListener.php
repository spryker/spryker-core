<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsBlockCategoryPositionStorageListener extends AbstractCmsBlockCategoryStorageListener
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $idPosition = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        $idCategories = $this->getQueryContainer()->queryCategoryIdsByPositionIds($idPosition)->find()->getData();

        $this->publish($idCategories);
    }
}
