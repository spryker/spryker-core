<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\CategoryExporter;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method CategoryExporterFactory getFactory()
 */
class CategoryExporterClient extends AbstractClient
{

    /**
     * @param string $locale
     *
     * @return array
     */
    public function getNavigationCategories($locale)
    {
        return $this->getFactory()->createNavigation()->getCategories($locale);
    }

    /**
     * @param array $categoryNode
     * @param string $locale
     *
     * @return array
     */
    public function getTreeFromCategoryNode(array $categoryNode, $locale)
    {
        return $this->getFactory()->createCategoryTreeBuilder()->createTreeFromCategoryNode($categoryNode, $locale);
    }

}
