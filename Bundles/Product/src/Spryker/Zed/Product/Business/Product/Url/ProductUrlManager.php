<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Url;

use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductUrlManager implements ProductUrlManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\\UrlProductUrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface $urlFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        ProductToUrlInterface $urlFacade,
        ProductToTouchInterface $touchFacade,
        ProductToLocaleInterface $localeFacade,
        ProductQueryContainerInterface $productQueryContainer,
        ProductUrlGeneratorInterface $urlGenerator
    ) {
        $this->urlFacade = $urlFacade;
        $this->touchFacade = $touchFacade;
        $this->localeFacade = $localeFacade;
        $this->productQueryContainer = $productQueryContainer;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstract)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $productUrl = $this->urlGenerator->generateProductUrl($productAbstract);

        foreach ($productUrl->getUrls() as $url) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer
                ->setUrl($url->getUrl())
                ->setFkLocale($url->getLocale()->getIdLocale())
                ->setResourceId($productAbstract->requireIdProductAbstract()->getIdProductAbstract())
                ->setResourceType(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT);

            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        }

        $this->productQueryContainer->getConnection()->commit();

        return $productUrl;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstract
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstract)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $productUrl = $this->urlGenerator->generateProductUrl($productAbstract);

        foreach ($productUrl->getUrls() as $url) {
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
                $productAbstract->requireIdProductAbstract()->getIdProductAbstract(),
                $url->getLocale()->getIdLocale()
            );

            $urlTransfer
                ->setUrl($url->getUrl())
                ->setFkLocale($url->getLocale()->getIdLocale())
                ->setResourceId($productAbstract->getIdProductAbstract())
                ->setResourceType(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT);

            $this->urlFacade->saveUrlAndTouch($urlTransfer);
        }

        $this->productQueryContainer->getConnection()->commit();

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
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
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
        $this->productQueryContainer->getConnection()->beginTransaction();

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
                $productAbstract->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            if ($urlTransfer->getIdUrl()) {
                $this->urlFacade->deleteUrlAndTouch($urlTransfer);
            }
        }

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlActive(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
                $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            $this->urlFacade->touchUrlActive(
                $urlTransfer->requireIdUrl()->getIdUrl()
            );
        }

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlDeleted(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
                $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale()
            );

            if (!$urlTransfer->getIdUrl()) {
                continue;
            }

            $this->urlFacade->touchUrlDeleted(
                $urlTransfer->requireIdUrl()->getIdUrl()
            );
        }

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale)
    {
        $urlEntity = $this->productQueryContainer
            ->queryUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale)
            ->findOneOrCreate();

        $urlTransfer = (new UrlTransfer())
            ->fromArray($urlEntity->toArray(), true);

        return $urlTransfer;
    }

}
