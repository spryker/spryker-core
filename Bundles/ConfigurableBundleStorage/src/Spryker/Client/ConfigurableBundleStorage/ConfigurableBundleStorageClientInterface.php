<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

interface ConfigurableBundleStorageClientInterface
{
    /**
     * Specification:
     * - Finds a configurable bundle template within Storage with a given ID.
     * - Returns null if configurable bundle template was not found.
     * - Populates ConfigurableBundleTemplateStorageTransfer::imageSets using provided locale.
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate, string $localeName): ?ConfigurableBundleTemplateStorageTransfer;

    /**
     * Specification:
     * - Finds configurable bundle template within Storage with a given uuid.
     * - Returns ConfigurableBundleTemplateStorageTransfer if found, null otherwise.
     *
     * @api
     *
     * @param string $configurableBundleTemplateUuid
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorageByUuid(
        string $configurableBundleTemplateUuid,
        string $localeName
    ): ?ConfigurableBundleTemplateStorageTransfer;

    /**
     * Specification:
     * - Finds product concrete Storage records by skus and locale.
     * - Expands results with product images data.
     * - Returns array of ProductViewTransfer indexed by sku.
     *
     * @api
     *
     * @param string[] $skus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductConcretesBySkusAndLocale(array $skus, string $localeName): array;
}
