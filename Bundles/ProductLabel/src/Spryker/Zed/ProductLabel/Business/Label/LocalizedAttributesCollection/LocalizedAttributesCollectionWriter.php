<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;

class LocalizedAttributesCollectionWriter implements LocalizedAttributesCollectionWriterInterface
{

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[] $localizedAttributesTransferCollection
     *
     * @return void
     */
    public function replace(ArrayObject $localizedAttributesTransferCollection)
    {
        foreach ($localizedAttributesTransferCollection as $localizedAttributesTransfer) {
            $localizedAttributesEntity = $this->getEntityFromTransfer($localizedAttributesTransfer);
            $localizedAttributesEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes
     */
    protected function getEntityFromTransfer(ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        $localizedAttributesEntity = new SpyProductLabelLocalizedAttributes();
        $localizedAttributesEntity->fromArray($localizedAttributesTransfer->toArray());

        return $localizedAttributesEntity;
    }

}
