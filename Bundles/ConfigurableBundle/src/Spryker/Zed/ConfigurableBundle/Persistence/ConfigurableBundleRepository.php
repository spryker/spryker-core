<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundlePersistenceFactory getFactory()
 */
class ConfigurableBundleRepository extends AbstractRepository implements ConfigurableBundleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ?ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateQuery = $this->getFactory()->createConfigurableBundleTemplateQuery();
        $configurableBundleTemplateQuery = $this->setConfigurableBundleTemplateFilters(
            $configurableBundleTemplateQuery,
            $configurableBundleTemplateFilterTransfer
        );

        $configurableBundleTemplateEntity = $configurableBundleTemplateQuery->find()->getFirst();

        if (!$configurableBundleTemplateEntity) {
            return null;
        }

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapConfigurableBundleTemplateEntityToTransfer($configurableBundleTemplateEntity, new ConfigurableBundleTemplateTransfer());
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery $configurableBundleTemplateQuery
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    protected function setConfigurableBundleTemplateFilters(
        SpyConfigurableBundleTemplateQuery $configurableBundleTemplateQuery,
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): SpyConfigurableBundleTemplateQuery {
        if ($configurableBundleTemplateFilterTransfer->getIdConfigurableBundleTemplate()) {
            $configurableBundleTemplateQuery->filterByIdConfigurableBundleTemplate(
                $configurableBundleTemplateFilterTransfer->getIdConfigurableBundleTemplate()
            );
        }

        $configurableBundleTemplateQuery->limit(1);

        return $configurableBundleTemplateQuery;
    }
}
