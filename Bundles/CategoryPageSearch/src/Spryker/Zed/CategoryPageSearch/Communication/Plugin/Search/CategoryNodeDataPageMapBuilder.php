<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Communication\Plugin\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\Search\Dependency\Plugin\NamedPageMapInterface;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Communication\CategoryPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchFacadeInterface getFacade()
 */
class CategoryNodeDataPageMapBuilder extends AbstractPlugin implements NamedPageMapInterface
{
    public const TYPE_CATEGORY = 'category';

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $categoryData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $categoryData, LocaleTransfer $localeTransfer)
    {
        $pageMapTransfer = (new PageMapTransfer())
            ->setStore(Store::getInstance()->getStoreName())
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(static::TYPE_CATEGORY)
            ->setIsActive($categoryData['spy_category']['is_active'] && $categoryData['spy_category']['is_searchable']);

        $categoryAttribute = $categoryData['spy_category']['spy_category_attributes'][0];

        /*
         * Here you can hard code which category data will be used for which search functionality
         */
        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, 'id_category', $categoryData['fk_category'])
            ->addSearchResultData($pageMapTransfer, 'name', $categoryAttribute['name'])
            ->addSearchResultData($pageMapTransfer, 'url', $categoryData['spy_urls'][0]['url'])
            ->addSearchResultData($pageMapTransfer, 'type', static::TYPE_CATEGORY)
            ->addFullTextBoosted($pageMapTransfer, $categoryAttribute['name'])
            ->addFullText($pageMapTransfer, isset($categoryAttribute['meta_title']) ? $categoryAttribute['meta_title'] : '')
            ->addFullText($pageMapTransfer, isset($categoryAttribute['meta_keywords']) ? $categoryAttribute['meta_keywords'] : '')
            ->addFullText($pageMapTransfer, isset($categoryAttribute['meta_description']) ? $categoryAttribute['meta_description'] : '')
            ->addSuggestionTerms($pageMapTransfer, $categoryAttribute['name'])
            ->addCompletionTerms($pageMapTransfer, $categoryAttribute['name']);

        return $pageMapTransfer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return CategoryPageSearchConstants::CATEGORY_NODE_RESOURCE_NAME;
    }
}
