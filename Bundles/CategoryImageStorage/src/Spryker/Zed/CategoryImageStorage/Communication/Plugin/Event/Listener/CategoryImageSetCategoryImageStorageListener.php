<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageSetToCategoryImageTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Communication\CategoryImageStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryImageStorage\Business\CategoryImageStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig getConfig()
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

        $categoryImageSetIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys(
                $transfers,
                SpyCategoryImageSetToCategoryImageTableMap::COL_FK_CATEGORY_IMAGE_SET
            );
        $categoryIds = $this->getRepository()
            ->getCategoryIdsByCategoryImageSetIds($categoryImageSetIds)
            ->getData();

        $this->getFacade()->publishCategoryImages($categoryIds);
    }
}
