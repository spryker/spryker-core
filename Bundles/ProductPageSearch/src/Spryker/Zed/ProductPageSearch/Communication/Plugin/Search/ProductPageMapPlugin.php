<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\Search\Dependency\Plugin\NamedPageMapInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class ProductPageMapPlugin extends AbstractPlugin implements NamedPageMapInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $data, LocaleTransfer $locale)
    {
        $pageMapTransfer = (new PageMapTransfer())
            ->setStore($data['store'])
            ->setLocale($data['locale'])
            ->setType($data['type'])
            ->setIsActive($data['is_active']);

        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, 'id_product_abstract', $data['id_product_abstract'])
            ->addSearchResultData($pageMapTransfer, 'abstract_sku', $data['sku'])
            ->addSearchResultData($pageMapTransfer, 'abstract_name', $data['name'])
            ->addSearchResultData($pageMapTransfer, 'url', $data['url'])
            ->addSearchResultData($pageMapTransfer, 'type', 'product_abstract')
            ->addFullTextBoosted($pageMapTransfer, $data['name'])
            ->addFullTextBoosted($pageMapTransfer, $data['sku'])
            ->addFullText($pageMapTransfer, $data['concrete_names'])
            ->addFullText($pageMapTransfer, $data['concrete_skus'])
            ->addFullText($pageMapTransfer, $data['abstract_description'])
            ->addFullText($pageMapTransfer, $data['concrete_description'])
            ->addSuggestionTerms($pageMapTransfer, $data['name'])
            ->addCompletionTerms($pageMapTransfer, $data['name'])
            ->addStringSort($pageMapTransfer, 'name', $data['name']);

        $this->expandProductPageMap($pageMapTransfer, $pageMapBuilder, $data, $locale);

        $pageMapTransfer = $this
            ->getFactory()->getProductSearchFacade()
            ->mapDynamicProductAttributes($pageMapBuilder, $pageMapTransfer, $data['attributes']);

        return $pageMapTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    protected function expandProductPageMap(PageMapTransfer $pageMapTransfer, PageMapBuilderInterface $pageMapBuilder, array $productData, LocaleTransfer $localeTransfer)
    {
        foreach ($this->getFactory()->getProductPageMapExpanderPlugins() as $productPageMapExpander) {
            $pageMapTransfer = $productPageMapExpander->expandProductPageMap($pageMapTransfer, $pageMapBuilder, $productData, $localeTransfer);
        }

        return $pageMapTransfer;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return ProductPageSearchConstants::PRODUCT_ABSTRACT_RESOURCE_NAME;
    }
}
