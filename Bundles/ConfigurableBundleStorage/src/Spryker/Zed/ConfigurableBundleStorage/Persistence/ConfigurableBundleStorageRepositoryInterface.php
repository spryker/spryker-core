<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ConfigurableBundleStorageRepositoryInterface
{
    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplate[]
     */
    public function getConfigurableBundleTemplateEntityMap(array $configurableBundleTemplateIds): array;

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage[]
     */
    public function getConfigurableBundleTemplateStorageEntityMap(array $configurableBundleTemplateIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Generated\Shared\Transfer\SpyConfigurableBundleTemplateStorageEntityTransfer[]
     */
    public function getFilteredConfigurableBundleTemplateStorageEntities(FilterTransfer $filterTransfer, array $configurableBundleTemplateIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer[]
     */
    public function getConfigurableBundleTemplatesByFilter(FilterTransfer $filterTransfer): array;
}
