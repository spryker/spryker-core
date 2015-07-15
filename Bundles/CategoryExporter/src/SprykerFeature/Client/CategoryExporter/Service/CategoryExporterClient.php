<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\CategoryExporter\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
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
        return $this->getDependencyContainer()->createNavigation()->getCategories($locale);
    }

    /**
     * @param array $categoryNode
     * @param string $locale
     *
     * @return array
     */
    public function getTreeFromCategoryNode(array $categoryNode, $locale)
    {
        return $this->getDependencyContainer()->createCategoryTreeBuilder()->createTreeFromCategoryNode($categoryNode, $locale);
    }

}
