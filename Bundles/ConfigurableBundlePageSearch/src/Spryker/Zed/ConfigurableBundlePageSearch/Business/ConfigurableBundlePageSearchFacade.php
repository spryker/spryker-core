<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business;

use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Business\ConfigurableBundlePageSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface getEntityManager()
 */
class ConfigurableBundlePageSearchFacade extends AbstractFacade implements ConfigurableBundlePageSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function publishConfigurableBundleTemplates(array $configurableBundleTemplateIds): void
    {
        $this->getFactory()
            ->createConfigurableBundleTemplatePublisher()
            ->publish($configurableBundleTemplateIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function unpublishConfigurableBundleTemplates(array $configurableBundleTemplateIds): void
    {
        $this->getFactory()
            ->createConfigurableBundleTemplateUnpublisher()
            ->unpublish($configurableBundleTemplateIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api

     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer $configurableBundleTemplatePageSearchFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer
     */
    public function getConfigurableBundleTemplatePageSearchCollection(
        ConfigurableBundleTemplatePageSearchFilterTransfer $configurableBundleTemplatePageSearchFilterTransfer
    ): ConfigurableBundleTemplatePageSearchCollectionTransfer {
        return $this->getRepository()->getConfigurableBundleTemplatePageSearchCollection($configurableBundleTemplatePageSearchFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer
     */
    public function getConfigurableBundleTemplateCollection(FilterTransfer $filterTransfer): ConfigurableBundleTemplateCollectionTransfer
    {
        return $this->getRepository()->getConfigurableBundleTemplateCollection($filterTransfer);
    }
}
