<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\DataMapper\Elasticsearch;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

class ProductAbstractSearchDataMapper extends AbstractProductSearchDataMapper
{
    protected const FACET_NAME = 'facet-name';
    protected const FACET_VALUE = 'facet-value';
    protected const ALL_PARENTS = 'all-parents';
    protected const DIRECT_PARENTS = 'direct-parents';

    /**
     * @var \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface
     */
    protected $pageMapBuilder;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchInterface
     */
    protected $productSearchFacade;

    /**
     * @var \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractPageMapExpanderPluginInterface[]
     */
    protected $productAbstractPageMapExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchInterface $productSearchFacade
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractPageMapExpanderPluginInterface[] $productAbstractPageMapExpanderPlugins
     */
    public function __construct(
        PageMapBuilderInterface $pageMapBuilder,
        ProductPageSearchToProductSearchInterface $productSearchFacade,
        array $productAbstractPageMapExpanderPlugins
    ) {
        parent::__construct();

        $this->pageMapBuilder = $pageMapBuilder;
        $this->productSearchFacade = $productSearchFacade;
        $this->productAbstractPageMapExpanderPlugins = $productAbstractPageMapExpanderPlugins;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(array $data, LocaleTransfer $localeTransfer): PageMapTransfer
    {
        $pageMapTransfer = (new PageMapTransfer())
            ->setStore($data['store'])
            ->setLocale($data['locale'])
            ->setType($data['type'])
            ->setIsActive($data['is_active']);

        $this->pageMapBuilder->addSearchResultData($pageMapTransfer, 'id_product_abstract', $data['id_product_abstract'])
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

        $this->expandProductPageMap($pageMapTransfer, $data, $localeTransfer);

        $pageMapTransfer = $this->productSearchFacade->mapDynamicProductAttributesToSearchData($this->pageMapBuilder, $pageMapTransfer, $data['attributes']);

        return $pageMapTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    protected function expandProductPageMap(PageMapTransfer $pageMapTransfer, array $productData, LocaleTransfer $localeTransfer)
    {
        foreach ($this->productAbstractPageMapExpanderPlugins as $productPageMapExpander) {
            $pageMapTransfer = $productPageMapExpander->expandProductPageMap($pageMapTransfer, $this->pageMapBuilder, $productData, $localeTransfer);
        }

        return $pageMapTransfer;
    }
}
