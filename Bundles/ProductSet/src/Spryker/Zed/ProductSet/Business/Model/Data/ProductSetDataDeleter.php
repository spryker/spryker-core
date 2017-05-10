<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data;

use Orm\Zed\ProductSet\Persistence\SpyProductSet;
use Spryker\Zed\ProductSet\Business\Model\Data\Image\ProductSetImageDeleterInterface;
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
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\Image\ProductSetImageDeleterInterface
     */
    protected $productSetImageDeleter;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlDeleterInterface $productSetUrlDeleter
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\Image\ProductSetImageDeleterInterface $productSetImageDeleter
     */
    public function __construct(ProductSetUrlDeleterInterface $productSetUrlDeleter, ProductSetImageDeleterInterface $productSetImageDeleter)
    {
        $this->productSetUrlDeleter = $productSetUrlDeleter;
        $this->productSetImageDeleter = $productSetImageDeleter;
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
    public function executeDeleteProductSetDataTransaction(SpyProductSet $productSetEntity)
    {
        $this->deleteProductSetDataEntities($productSetEntity);
        $this->productSetUrlDeleter->deleteUrl($productSetEntity->getIdProductSet());
        $this->productSetImageDeleter->deleteImageSets($productSetEntity->getIdProductSet());
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
