<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface;

class LabelWriter implements LabelWriterInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface
     */
    protected $localizedAttributesCollectionWriter;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter
     */
    public function __construct(LocalizedAttributesCollectionWriterInterface $localizedAttributesCollectionWriter)
    {
        $this->localizedAttributesCollectionWriter = $localizedAttributesCollectionWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function create(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelEntity = $this->createEntityFromTransfer($productLabelTransfer);

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
