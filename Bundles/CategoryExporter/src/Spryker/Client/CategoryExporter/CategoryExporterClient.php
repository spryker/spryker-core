<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryExporter;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CategoryExporter\CategoryExporterFactory getFactory()
 */
class CategoryExporterClient extends AbstractClient implements CategoryExporterClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $locale
     *
     * @return array
     */
    public function getNavigationCategories($locale)
    {
        return $this->getFactory()->createNavigation()->getCategories($locale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
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
