<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot;
use Propel\Runtime\Collection\Collection;

class ConfigurableBundleMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $configurableBundleTemplateEntities
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer
     */
    public function mapTemplateEntityCollectionToTemplateTransferCollection(
        Collection $configurableBundleTemplateEntities
    ): ConfigurableBundleTemplateCollectionTransfer {
        $configurableBundleTemplateCollectionTransfer = new ConfigurableBundleTemplateCollectionTransfer();

        foreach ($configurableBundleTemplateEntities as $configurableBundleTemplateEntity) {
            $configurableBundleTemplateTransfer = $this->mapTemplateEntityToTemplateTransfer(
                $configurableBundleTemplateEntity,
                new ConfigurableBundleTemplateTransfer()
            );

            $configurableBundleTemplateCollectionTransfer->addConfigurableBundleTemplate($configurableBundleTemplateTransfer);
        }

        return $configurableBundleTemplateCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $configurableBundleTemplateSlotEntities
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer
     */
    public function mapTemplateSlotEntityCollectionToTemplateSlotTransferCollection(
        Collection $configurableBundleTemplateSlotEntities
    ): ConfigurableBundleTemplateSlotCollectionTransfer {
        $configurableBundleTemplateSlotCollectionTransfer = new ConfigurableBundleTemplateSlotCollectionTransfer();

        foreach ($configurableBundleTemplateSlotEntities as $configurableBundleTemplateSlotEntity) {
            $configurableBundleTemplateSlotTransfer = $this->mapTemplateSlotEntityToTemplateSlotTransfer(
                $configurableBundleTemplateSlotEntity,
                new ConfigurableBundleTemplateSlotTransfer()
            );

            $configurableBundleTemplateSlotCollectionTransfer->addConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
        }

        return $configurableBundleTemplateSlotCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate
     */
    public function mapTemplateTransferToTemplateEntity(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
    ): SpyConfigurableBundleTemplate {
        $configurableBundleTemplateEntity->fromArray($configurableBundleTemplateTransfer->modifiedToArray());

        return $configurableBundleTemplateEntity;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function mapTemplateEntityToTemplateTransfer(
        SpyConfigurableBundleTemplate $configurableBundleTemplateEntity,
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        return $configurableBundleTemplateTransfer->fromArray($configurableBundleTemplateEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot
     */
    public function mapTemplateSlotTransferToTemplateSlotEntity(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity
    ): SpyConfigurableBundleTemplateSlot {
        $configurableBundleTemplateSlotEntity->fromArray($configurableBundleTemplateSlotTransfer->modifiedToArray());

        $configurableBundleTemplateSlotEntity
            ->setFkProductList($configurableBundleTemplateSlotTransfer->getProductList()->getIdProductList());

        return $configurableBundleTemplateSlotEntity;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function mapTemplateSlotEntityToTemplateSlotTransfer(
        SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity,
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer = $configurableBundleTemplateSlotTransfer
            ->fromArray($configurableBundleTemplateSlotEntity->toArray(), true);

        $configurableBundleTemplateTransfer = $this->mapTemplateEntityToTemplateTransfer(
            $configurableBundleTemplateSlotEntity->getSpyConfigurableBundleTemplate(),
            new ConfigurableBundleTemplateTransfer()
        );

        $productListTransfer = (new ProductListTransfer())
            ->setIdProductList($configurableBundleTemplateSlotEntity->getFkProductList());

        return $configurableBundleTemplateSlotTransfer
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer)
            ->setProductList($productListTransfer);
    }
}
