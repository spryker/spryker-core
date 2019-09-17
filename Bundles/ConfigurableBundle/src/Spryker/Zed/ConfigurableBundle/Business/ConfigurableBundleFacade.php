<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
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
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
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
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleTemplateWriter()
            ->updateConfigurableBundleTemplate($configurableBundleTemplateTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateTransfer
    {
        return $this->getFactory()
            ->createConfigurableBundleTemplateReader()
            ->findConfigurableBundleTemplateById($idConfigurableBundleTemplate);
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
}
