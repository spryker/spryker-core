<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;

class LabelWriter implements LabelWriterInterface
{

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

}
