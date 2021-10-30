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
     * @param array<int> $configurableBundleTemplateIds
     *
     * @return array<\Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorage>
     */
    public function getConfigurableBundleTemplateStorageEntityMap(array $configurableBundleTemplateIds): array;

    /**
     * @param array<int> $configurableBundleTemplateIds
     *
     * @return array<array<\Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorage>>
     */
    public function getConfigurableBundleTemplateImageStorageEntityMap(array $configurableBundleTemplateIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $configurableBundleTemplateIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getFilteredConfigurableBundleTemplateStorageDataTransfers(
        FilterTransfer $filterTransfer,
        array $configurableBundleTemplateIds
    ): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $configurableBundleTemplateIds
     *
     * @return array<\Generated\Shared\Transfer\SpyConfigurableBundleTemplateImageStorageEntityTransfer>
     */
    public function getFilteredConfigurableBundleTemplateImageStorageEntities(FilterTransfer $filterTransfer, array $configurableBundleTemplateIds): array;
}
