<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Generated\Shared\Transfer\ConfigurableBundleResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Business\ConfigurableBundleBusinessFactory getFactory()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface getRepository()
 */
class ConfigurableBundleFacade extends AbstractFacade implements ConfigurableBundleFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateWriter()
            ->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateWriter()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ?ConfigurableBundleTemplateTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateReader()
            ->findConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->getFactory()
            ->createConfigurableBundleTemplateWriter()
            ->deleteConfigurableBundleTemplateById($idConfigurableBundleTemplate);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createInactiveConfiguredBundleItemFilter()
            ->filterInactiveItems($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function activateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->getFactory()
            ->createConfigurableBundleTemplateWriter()
            ->activateConfigurableBundleTemplateById($idConfigurableBundleTemplate);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deactivateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void
    {
        $this->getFactory()
            ->createConfigurableBundleTemplateWriter()
            ->deactivateConfigurableBundleTemplateById($idConfigurableBundleTemplate);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function checkProductListUsageAmongSlots(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotReader()
            ->checkProductListUsageAmongSlots($productListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function getConfigurableBundleTemplateSlotCollection(ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer): array
    {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotReader()
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function createConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotWriter()
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function updateConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotWriter()
            ->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateSlotById(int $idConfigurableBundleTemplateSlot): void
    {
        $this->getEntityManager()->deleteConfigurableBundleTemplateSlotById($idConfigurableBundleTemplateSlot);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer|null
     */
    public function findConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ?ConfigurableBundleTemplateSlotTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotReader()
            ->findConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return int
     */
    public function getProductListIdByIdConfigurableBundleTemplate(int $idConfigurableBundleTemplate): int
    {
        return $this->getRepository()->getProductListIdByIdConfigurableBundleTemplate($idConfigurableBundleTemplate);
    }
}
