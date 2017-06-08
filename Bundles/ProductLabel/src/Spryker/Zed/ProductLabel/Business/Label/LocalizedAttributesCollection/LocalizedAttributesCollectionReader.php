<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;

class LocalizedAttributesCollectionReader implements LocalizedAttributesCollectionReaderInterface
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
     * @param int $idProductLabel
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[]
     */
    public function findAllByIdProductLabel($idProductLabel)
    {
        $localizedAttributesEntities = $this->findEntitiesByIdProductLabel($idProductLabel);
        $localizedAttributesTransferCollection = new ArrayObject();

        foreach ($localizedAttributesEntities as $localizedAttributesEntity) {
            $localizedAttributesTransferCollection->append($this->getTransferFromEntity($localizedAttributesEntity));
        }

        return $localizedAttributesTransferCollection;
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes[]
     */
    protected function findEntitiesByIdProductLabel($idProductLabel)
    {
        return $this
            ->queryContainer
            ->queryLocalizedAttributesByIdProductLabel($idProductLabel)
            ->find();
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $localizedAttributesEntity
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer
     */
    protected function getTransferFromEntity(SpyProductLabelLocalizedAttributes $localizedAttributesEntity)
    {
        $localizedAttributesTransfer = new ProductLabelLocalizedAttributesTransfer();
        $localizedAttributesTransfer->fromArray($localizedAttributesEntity->toArray(), true);

        return $localizedAttributesTransfer;
    }

}
