<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data;

use Orm\Zed\ProductSet\Persistence\SpyProductSet;
use Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlDeleterInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductSetDataDeleter implements ProductSetDataDeleterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlDeleterInterface
     */
    protected $productSetUrlDeleter;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlDeleterInterface $productSetUrlDeleter
     */
    public function __construct(ProductSetUrlDeleterInterface $productSetUrlDeleter)
    {
        $this->productSetUrlDeleter = $productSetUrlDeleter;
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return void
     */
    public function deleteProductSetData(SpyProductSet $productSetEntity)
    {
        $this->handleDatabaseTransaction(function () use ($productSetEntity) {
            $this->executeDeleteProductSetDataTransaction($productSetEntity);
        });
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return void
     */
    protected function executeDeleteProductSetDataTransaction(SpyProductSet $productSetEntity)
    {
        $this->deleteProductSetDataEntities($productSetEntity);
        $this->productSetUrlDeleter->deleteUrl($productSetEntity->getIdProductSet());
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return void
     */
    protected function deleteProductSetDataEntities(SpyProductSet $productSetEntity)
    {
        $productSetEntity->getSpyProductSetDatas()->delete();
    }
}
