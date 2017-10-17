<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryExporter;

interface CategoryExporterClientInterface
{
    /**
     * @api
     *
     * @param string $locale
     *
     * @return array
     */
    public function getNavigationCategories($locale);

    /**
     * @api
     *
     * @param array $categoryNode
     * @param string $locale
     *
     * @return array
     */
    public function getTreeFromCategoryNode(array $categoryNode, $locale);
}
