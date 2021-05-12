<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryExporter;

interface CategoryExporterClientInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $locale
     *
     * @return array
     */
    public function getNavigationCategories($locale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $categoryNode
     * @param string $locale
     *
     * @return array
     */
    public function getTreeFromCategoryNode(array $categoryNode, $locale);
}
