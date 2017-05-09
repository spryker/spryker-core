<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class LocalizedAttributesCollectionWriter implements LocalizedAttributesCollectionWriterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[] $localizedAttributesTransferCollection
     *
     * @return void
     */
    public function set(ArrayObject $localizedAttributesTransferCollection)
    {
        $this->handleDatabaseTransaction(function () use ($localizedAttributesTransferCollection) {
            $this->executeSetTransaction($localizedAttributesTransferCollection);
        });
    }

    /**
     * @param \ArrayObject $localizedAttributesTransferCollection
     *
     * @return void
     */
    protected function executeSetTransaction(ArrayObject $localizedAttributesTransferCollection)
    {
        foreach ($localizedAttributesTransferCollection as $localizedAttributesTransfer) {
            $this->assertLocalizedAttributes($localizedAttributesTransfer);
            $this->persistLocalizedAttributes($localizedAttributesTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return void
     */
    protected function assertLocalizedAttributes(ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        $localizedAttributesTransfer
            ->requireFkLocale()
            ->requireFkProductLabel();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return void
     */
    protected function persistLocalizedAttributes(ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        $localizedAttributesEntity = $this->createEntityFromTransfer($localizedAttributesTransfer);
        $localizedAttributesEntity->save();

        $this->updateTransferFromEntity($localizedAttributesTransfer, $localizedAttributesEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes
     */
    protected function createEntityFromTransfer(ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer)
    {
        $localizedAttributesEntity = new SpyProductLabelLocalizedAttributes();
        $localizedAttributesEntity->fromArray($localizedAttributesTransfer->toArray());

        return $localizedAttributesEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $localizedAttributesEntity
     *
     * @return void
     */
    protected function updateTransferFromEntity(
        ProductLabelLocalizedAttributesTransfer $localizedAttributesTransfer,
        SpyProductLabelLocalizedAttributes $localizedAttributesEntity
    ) {
        $localizedAttributesTransfer->fromArray($localizedAttributesEntity->toArray(), true);
    }

}
