<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot;

class ConfigurableBundleMapper
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate
     */
    public function mapConfigurableBundleTemplateTransferToEntity(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        SpyConfigurableBundleTemplate $configurableBundleTemplateEntity
    ): SpyConfigurableBundleTemplate {
        $configurableBundleTemplateEntity->fromArray(
            $configurableBundleTemplateTransfer->toArray()
        );

        return $configurableBundleTemplateEntity;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate $configurableBundleTemplateEntiy
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function mapConfigurableBundleTemplateEntityToTransfer(
        SpyConfigurableBundleTemplate $configurableBundleTemplateEntiy,
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateTransfer {
        return $configurableBundleTemplateTransfer->fromArray(
            $configurableBundleTemplateEntiy->toArray(),
            true
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot
     */
    public function mapConfigurableBundleTemplateSlotTransferToEntity(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity
    ): SpyConfigurableBundleTemplateSlot {
        $configurableBundleTemplateSlotEntity->fromArray(
            $configurableBundleTemplateSlotTransfer->toArray()
        );

        return $configurableBundleTemplateSlotEntity->setFkProductList(
            $configurableBundleTemplateSlotTransfer->getProductList()->getIdProductList()
        );
    }
    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function mapConfigurableBundleTemplateSlotEntityToTransfer(
        SpyConfigurableBundleTemplateSlot $configurableBundleTemplateSlotEntity,
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer->fromArray($configurableBundleTemplateSlotEntity->toArray(), true);

        $configurableBundleTemplateTransfer = $this->mapConfigurableBundleTemplateEntityToTransfer(
            $configurableBundleTemplateSlotEntity->getSpyConfigurableBundleTemplate(),
            new ConfigurableBundleTemplateTransfer()
        );

        $productListTransfer = (new ProductListTransfer())->setIdProductList(
            $configurableBundleTemplateSlotEntity->getFkProductList()
        );

        return $configurableBundleTemplateSlotTransfer->setProductList($productListTransfer)
            ->setConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }
}
