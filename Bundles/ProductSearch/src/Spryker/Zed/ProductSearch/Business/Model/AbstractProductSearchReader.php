<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

abstract class AbstractProductSearchReader
{
    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface $localeFacade
     */
    public function __construct(ProductSearchQueryContainerInterface $productSearchQueryContainer, ProductSearchToLocaleInterface $localeFacade)
    {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return int
     */
    protected function getIdLocale(?LocaleTransfer $localeTransfer = null)
    {
        if ($localeTransfer) {
            return $localeTransfer->getIdLocale();
        }

        return $this->localeFacade
            ->getCurrentLocale()
            ->getIdLocale();
    }
}
