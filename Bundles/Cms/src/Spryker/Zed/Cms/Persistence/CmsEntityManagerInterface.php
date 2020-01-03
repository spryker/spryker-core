<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

interface CmsEntityManagerInterface
{
    /**
     * Specification:
     * - Adds new relations between stores and cms page
     *
     * @param array $idStores
     * @param int $idCmsPage
     *
     * @return void
     */
    public function addStoreRelations(array $idStores, int $idCmsPage): void;

    /**
     * Specification:
     * - Remove relations between stores and cms page
     *
     * @param array $idStores
     * @param int $idCmsPage
     *
     * @return void
     */
    public function removeStoreRelations(array $idStores, int $idCmsPage): void;
}
