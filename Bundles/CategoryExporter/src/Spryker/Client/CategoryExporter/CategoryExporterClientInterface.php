<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\CategoryExporter;

interface CategoryExporterClientInterface
{

    /**
     * @param string $locale
     *
     * @return array
     */
    public function getNavigationCategories($locale);

    /**
     * @param array $categoryNode
     * @param string $locale
     *
     * @return array
     */
    public function getTreeFromCategoryNode(array $categoryNode, $locale);

}
