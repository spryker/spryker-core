<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Expander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;

class ProductClassProductAbstractMapExpander implements ProductClassProductAbstractMapExpanderInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_CLASS_NAMES = 'product_class_names';

    /**
     * @var string
     */
    protected const SEARCH_RESULT_KEY_PRODUCT_CLASS_NAMES = 'product-class-names';

    /**
     * @var string
     */
    protected const STRING_FACET_KEY_PRODUCT_CLASS_NAMES = 'product-class-names';

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array<string, mixed> $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductAbstractMapWithProductClasses(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        if (!isset($productData[static::PRODUCT_CLASS_NAMES]) || !is_array($productData[static::PRODUCT_CLASS_NAMES])) {
            return $pageMapTransfer;
        }

        $productClassNames = $productData[static::PRODUCT_CLASS_NAMES];

        if (!$productClassNames) {
            return $pageMapTransfer;
        }

        $pageMapBuilder->addSearchResultData($pageMapTransfer, static::SEARCH_RESULT_KEY_PRODUCT_CLASS_NAMES, $productClassNames);

        foreach ($productClassNames as $productClassName) {
            $pageMapBuilder->addStringFacet($pageMapTransfer, static::STRING_FACET_KEY_PRODUCT_CLASS_NAMES, $productClassName);
        }

        return $pageMapTransfer;
    }
}
