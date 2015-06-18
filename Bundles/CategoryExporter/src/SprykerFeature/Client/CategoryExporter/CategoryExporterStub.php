<?php

namespace SprykerFeature\Client\CategoryExporter;

use SprykerEngine\Client\Kernel\AbstractStub;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class CategoryExporterStub extends AbstractStub
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
