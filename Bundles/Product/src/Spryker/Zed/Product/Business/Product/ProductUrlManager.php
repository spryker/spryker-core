<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;

class ProductUrlManager implements ProductUrlManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductUrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface $urlFacade
     * @param \Spryker\Zed\Product\Business\Product\ProductUrlGeneratorInterface $urlGenerator
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductToUrlInterface $urlFacade,
        ProductToTouchInterface $touchFacade,
        ProductToLocaleInterface $localeFacade,
        ProductUrlGeneratorInterface $urlGenerator
    ) {
        $this->urlFacade = $urlFacade;
        $this->touchFacade = $touchFacade;
        $this->localeFacade = $localeFacade;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstract)
    {
        $productUrl = $this->urlGenerator->generateProductUrl($productAbstract);

        foreach ($productUrl->getUrls() as $url) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer
                ->setUrl($url->getUrl())
                ->setFkLocale($url->getLocale()->getIdLocale())
                ->setResourceId($productAbstract->requireIdProductAbstract()->getIdProductAbstract())
                ->setResourceType(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT);

            $this->urlFacade->saveUrl($urlTransfer);
        }

        $this->touchProductUrlActive($productAbstract);

        return $productUrl;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstract)
    {
        $productUrl = $this->urlGenerator->generateProductUrl($productAbstract);

        foreach ($productUrl->getUrls() as $url) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $productAbstract->requireIdProductAbstract()->getIdProductAbstract(),
                $url->getLocale()->getIdLocale()
            );

            $urlTransfer
                ->setUrl($url->getUrl())
                ->setFkLocale($url->getLocale()->getIdLocale())
                ->setResourceId($productAbstract->getIdProductAbstract())
                ->setResourceType(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT);

            $this->urlFacade->saveUrl($urlTransfer);
        }

        $this->touchProductUrlActive($productAbstract);

        return $productUrl;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstract)
    {
        $productUrl = new ProductUrlTransfer();
        $productUrl->setAbstractSku($productAbstract->getSku());

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $productAbstract->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            $localizedUrl = new LocalizedUrlTransfer();
            $localizedUrl
                ->setUrl($urlTransfer->getUrl())
                ->setLocale($localeTransfer);

            $productUrl->addUrl($localizedUrl);
        }

        return $productUrl;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return void
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstract)
    {
        $this->touchProductUrlDeleted($productAbstract); //TODO Url facade does that in deleteUrl(), but not in other methods

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $productAbstract->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            if ($urlTransfer->getIdUrl()) {
                $this->urlFacade->deleteUrl($urlTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return void
     */
    public function touchProductUrlActive(ProductAbstractTransfer $productAbstract)
    {
        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $productAbstract->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            $this->urlFacade->touchUrlActive(
                $urlTransfer->requireIdUrl()->getIdUrl()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return void
     */
    public function touchProductUrlDeleted(ProductAbstractTransfer $productAbstract)
    {
        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->urlFacade->getUrlByIdProductAbstractAndIdLocale(
                $productAbstract->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            if (!$urlTransfer->getIdUrl()) {
                continue;
            }

            $this->urlFacade->touchUrlDeleted(
                $urlTransfer->requireIdUrl()->getIdUrl()
            );
        }
    }

}
