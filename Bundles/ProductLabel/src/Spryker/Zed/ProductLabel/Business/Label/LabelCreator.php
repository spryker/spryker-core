<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;

class LabelCreator implements LabelCreatorInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface
     */
    protected $localizedAttributesCollectionWriter;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     */
    public function __construct(
        LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter,
        ProductLabelQueryContainerInterface $queryContainer
    ) {
        $this->localizedAttributesCollectionWriter = $localizedAttributesCollectionWriter;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function create(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelEntity = $this->createEntityFromTransfer($productLabelTransfer);
        $this->setPosition($productLabelEntity);

        $productLabelEntity->save();

        $productLabelTransfer->setIdProductLabel($productLabelEntity->getIdProductLabel());
        $this->createLocalizedAttributesCollection($productLabelTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function createEntityFromTransfer(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelEntity = new SpyProductLabel();
        $productLabelEntity->fromArray($productLabelTransfer->toArray());

        return $productLabelEntity;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return void
     */
    protected function setPosition(SpyProductLabel $productLabelEntity)
    {
        $productLabelEntity->setPosition($this->getMaxPosition() + 1);
    }

    /**
     * @return int
     */
    protected function getMaxPosition()
    {
        return (int)$this
            ->queryContainer
            ->queryMaxPosition()
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function createLocalizedAttributesCollection(ProductLabelTransfer $productLabelTransfer)
    {
        foreach ($productLabelTransfer->getLocalizedAttributesCollection() as $localizedAttributesTransfer) {
            $localizedAttributesTransfer->setFkProductLabel($productLabelTransfer->getIdProductLabel());
        }

        $this->localizedAttributesCollectionWriter->replace(
            $productLabelTransfer->getLocalizedAttributesCollection()
        );
    }

}
