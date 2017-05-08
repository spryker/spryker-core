<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Spryker\Zed\ProductLabel\Business\Label;


use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;

class LabelUpdater implements LabelUpdaterInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     */
    public function __construct(ProductLabelQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function update(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelTransfer->requireIdProductLabel();

        $productLabelEntity = $this->getEntityById($productLabelTransfer->getIdProductLabel());
        $productLabelEntity = $this->updateEntityFromTransfer($productLabelEntity, $productLabelTransfer);
        $productLabelEntity->save();
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function getEntityById($idProductLabel)
    {
        $productLabelEntity = $this
            ->queryContainer
            ->queryProductLabelById($idProductLabel)
            ->findOne();

        if (!$productLabelEntity) {

        }

        return $productLabelEntity;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function updateEntityFromTransfer(
        SpyProductLabel $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ) {
        $productLabelEntity->fromArray($productLabelTransfer->toArray());

        return $productLabelEntity;
    }

}
