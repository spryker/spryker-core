<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUtilTextInterface;

class ProductUrlGenerator implements ProductUrlGeneratorInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUtilTextInterface
     */
    protected $utilTextFacade;

    /**
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToUtilTextInterface $utilTextFacade
     */
    public function __construct(
        ProductAbstractManagerInterface $productAbstractManager,
        ProductToLocaleInterface $localeFacade,
        ProductToUtilTextInterface $utilTextFacade
    ) {
        $this->productAbstractManager = $productAbstractManager;
        $this->localeFacade = $localeFacade;
        $this->utilTextFacade = $utilTextFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function generateProductUrl(ProductAbstractTransfer $productAbstract)
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();

        $productUrlTransfer = new ProductUrlTransfer();
        $productUrlTransfer->setAbstractSku($productAbstract->getSku());

        foreach ($availableLocales as $localeTransfer) {
            $url = $this->generateUrlByLocale($productAbstract, $localeTransfer);

            $localizedUrl = new LocalizedUrlTransfer();
            $localizedUrl->setLocale($localeTransfer);
            $localizedUrl->setUrl($url);

            $productUrlTransfer->addUrl($localizedUrl);
        }

        return $productUrlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function generateUrlByLocale(ProductAbstractTransfer $productAbstract, LocaleTransfer $localeTransfer)
    {
        $productName = $this->utilTextFacade->generateSlug(
            $this->productAbstractManager->getLocalizedProductAbstractName($productAbstract, $localeTransfer)
        );

        return '/' . mb_substr($localeTransfer->getLocaleName(), 0, 2) . '/' . $productName . '-' . $productAbstract->getIdProductAbstract();
    }

}
