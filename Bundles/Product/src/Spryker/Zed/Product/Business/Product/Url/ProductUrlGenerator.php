<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Url;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Spryker\Zed\Product\Business\Product\NameGenerator\ProductAbstractNameGeneratorInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface;
use Spryker\Zed\Product\ProductConfig;

class ProductUrlGenerator implements ProductUrlGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\Product\NameGenerator\ProductAbstractNameGeneratorInterface
     */
    protected $productAbstractNameGenerator;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Zed\Product\ProductConfig
     */
    protected ProductConfig $productConfig;

    /**
     * @var string
     */
    protected const STR_SEARCH = '_';

    /**
     * @var string
     */
    protected const STR_REPLACE = '-';

    /**
     * @param \Spryker\Zed\Product\Business\Product\NameGenerator\ProductAbstractNameGeneratorInterface $productAbstractNameGenerator
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface $utilTextService
     * @param \Spryker\Zed\Product\ProductConfig $productConfig
     */
    public function __construct(
        ProductAbstractNameGeneratorInterface $productAbstractNameGenerator,
        ProductToLocaleInterface $localeFacade,
        ProductToUtilTextInterface $utilTextService,
        ProductConfig $productConfig
    ) {
        $this->productAbstractNameGenerator = $productAbstractNameGenerator;
        $this->localeFacade = $localeFacade;
        $this->utilTextService = $utilTextService;
        $this->productConfig = $productConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function generateProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();

        $productUrlTransfer = new ProductUrlTransfer();
        $productUrlTransfer->setAbstractSku($productAbstractTransfer->getSku());

        foreach ($availableLocales as $localeTransfer) {
            $url = $this->generateUrlByLocale($productAbstractTransfer, $localeTransfer);

            $localizedUrl = new LocalizedUrlTransfer();
            $localizedUrl->setLocale($localeTransfer);
            $localizedUrl->setUrl($url);

            $productUrlTransfer->addUrl($localizedUrl);
        }

        return $productUrlTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductUrlTransfer>
     */
    public function generateProductsUrl(array $productAbstractTransfers): array
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();
        $productUrlTransfers = [];

        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $productUrlTransfer = new ProductUrlTransfer();
            $productUrlTransfer->setAbstractSku($productAbstractTransfer->getSku());

            foreach ($availableLocales as $localeTransfer) {
                $url = $this->generateUrlByLocale($productAbstractTransfer, $localeTransfer);

                $localizedUrl = new LocalizedUrlTransfer();
                $localizedUrl->setLocale($localeTransfer);
                $localizedUrl->setUrl($url);

                $productUrlTransfer->addUrl($localizedUrl);
            }
            $productUrlTransfers[] = $productUrlTransfer;
        }

        return $productUrlTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function generateUrlByLocale(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer)
    {
        $productName = $this->utilTextService->generateSlug(
            $this->productAbstractNameGenerator->getLocalizedProductAbstractName($productAbstractTransfer, $localeTransfer),
        );

        return $this->productConfig->isFullLocaleNamesInUrlEnabled() ?
            sprintf(
                '/%s/%s-%s',
                str_replace(static::STR_SEARCH, static::STR_REPLACE, strtolower($localeTransfer->getLocaleName())),
                $productName,
                $productAbstractTransfer->getIdProductAbstract(),
            )
            : '/' . mb_substr($localeTransfer->getLocaleName(), 0, 2) . '/' . $productName . '-' . $productAbstractTransfer->getIdProductAbstract();
    }
}
