<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Communication\CategoryImageStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryImageStorage\Business\CategoryImageStorageFacadeInterface getFacade()
 */
class CategoryImageSetCategoryImageStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param array $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName)
    {
        $this->preventTransaction();
        $categoryImageSetToCategoryImageIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($transfers);
        $categoryIds = $this->getRepository()->findCategoryIdsByCategoryImageSetToCategoryImageIds($categoryImageSetToCategoryImageIds)->getData();

        $this->getFacade()->publishCategoryImages($categoryIds);
    }
}
