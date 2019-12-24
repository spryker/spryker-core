<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function getConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateReader()
            ->getConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer
     */
    public function getConfigurableBundleTemplateCollection(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateCollectionTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateReader()
            ->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateCreator()
            ->createConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateUpdater()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function activateConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateUpdater()
            ->activateConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function deactivateConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateUpdater()
            ->deactivateConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function deleteConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateCleaner()
            ->deleteConfigurableBundleTemplate($configurableBundleTemplateFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function getConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotReader()
            ->getConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer
     */
    public function getConfigurableBundleTemplateSlotCollection(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotCollectionTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotReader()
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function createConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotCreator()
            ->createConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function updateConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotUpdater()
            ->updateConfigurableBundleTemplateSlot($configurableBundleTemplateSlotTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function deleteConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateSlotCleaner()
            ->deleteConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeInactiveConfiguredBundleItemsFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createConfiguredBundleItemCleaner()
            ->removeInactiveConfiguredBundleItemsFromQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function isProductListDeletable(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ProductListResponseTransfer {
        return $this->getFactory()
            ->createProductListDeleteChecker()
            ->isProductListDeletable($configurableBundleTemplateSlotFilterTransfer);
    }
}
