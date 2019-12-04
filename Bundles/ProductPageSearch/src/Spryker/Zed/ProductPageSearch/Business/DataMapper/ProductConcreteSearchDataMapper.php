<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\DataMapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

class ProductConcreteSearchDataMapper extends AbstractProductSearchDataMapper
{
    protected const KEY_ID_PRODUCT = 'id_product';

    /**
     * @var \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface
     */
    protected $pageMapBuilder;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchInterface
     */
    protected $productSearchFacade;

    /**
     * @var \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageMapExpanderPluginInterface[]
     */
    protected $productConcreteMapExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageMapExpanderPluginInterface[] $productConcreteMapExpanderPlugins
     */
    public function __construct(
        PageMapBuilderInterface $pageMapBuilder,
        array $productConcreteMapExpanderPlugins
    ) {
        parent::__construct();

        $this->pageMapBuilder = $pageMapBuilder;
        $this->productConcreteMapExpanderPlugins = $productConcreteMapExpanderPlugins;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapProductDataToSearchData(array $data, LocaleTransfer $localeTransfer): array
    {
        return $this->buildProductPageSearchData($data, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    protected function buildPageMap(array $data, LocaleTransfer $locale): PageMapTransfer
    {
        $pageMapTransfer = (new PageMapTransfer())
            ->setStore($data[ProductConcretePageSearchTransfer::STORE])
            ->setLocale($data[ProductConcretePageSearchTransfer::LOCALE])
            ->setType($data[ProductConcretePageSearchTransfer::TYPE])
            ->setIsActive($data[ProductConcretePageSearchTransfer::IS_ACTIVE]);

        $this->pageMapBuilder
            ->addSearchResultData($pageMapTransfer, static::KEY_ID_PRODUCT, $data[ProductConcretePageSearchTransfer::FK_PRODUCT])
            ->addSearchResultData($pageMapTransfer, ProductConcretePageSearchTransfer::FK_PRODUCT_ABSTRACT, $data[ProductConcretePageSearchTransfer::FK_PRODUCT_ABSTRACT])
            ->addSearchResultData($pageMapTransfer, ProductConcretePageSearchTransfer::ABSTRACT_SKU, $data[ProductConcretePageSearchTransfer::ABSTRACT_SKU])
            ->addSearchResultData($pageMapTransfer, ProductConcretePageSearchTransfer::SKU, $data[ProductConcretePageSearchTransfer::SKU])
            ->addSearchResultData($pageMapTransfer, ProductConcretePageSearchTransfer::TYPE, $data[ProductConcretePageSearchTransfer::TYPE])
            ->addSearchResultData($pageMapTransfer, ProductConcretePageSearchTransfer::NAME, $data[ProductConcretePageSearchTransfer::NAME])
            ->addFullTextBoosted($pageMapTransfer, $data[ProductConcretePageSearchTransfer::NAME])
            ->addFullTextBoosted($pageMapTransfer, $data[ProductConcretePageSearchTransfer::SKU])
            ->addSuggestionTerms($pageMapTransfer, $data[ProductConcretePageSearchTransfer::NAME])
            ->addSuggestionTerms($pageMapTransfer, $data[ProductConcretePageSearchTransfer::SKU])
            ->addCompletionTerms($pageMapTransfer, $data[ProductConcretePageSearchTransfer::NAME])
            ->addCompletionTerms($pageMapTransfer, $data[ProductConcretePageSearchTransfer::SKU])
            ->addStringSort($pageMapTransfer, ProductConcretePageSearchTransfer::NAME, $data[ProductConcretePageSearchTransfer::NAME]);

        $pageMapTransfer = $this->expandProductPageMap($pageMapTransfer, $data, $locale);

        return $pageMapTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    protected function expandProductPageMap(PageMapTransfer $pageMapTransfer, array $productData, LocaleTransfer $localeTransfer): PageMapTransfer
    {
        foreach ($this->productConcreteMapExpanderPlugins as $productConcreteMapExpanderPlugin) {
            $pageMapTransfer = $productConcreteMapExpanderPlugin->expand($pageMapTransfer, $this->pageMapBuilder, $productData, $localeTransfer);
        }

        return $pageMapTransfer;
    }
}
