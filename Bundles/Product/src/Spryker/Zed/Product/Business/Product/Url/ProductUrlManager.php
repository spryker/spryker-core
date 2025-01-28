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
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Business\Product\Trigger\ProductEventTriggerInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductUrlManager implements ProductUrlManagerInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const FIELD_LOCALE_ID = 'localeId';

    /**
     * @var string
     */
    protected const FIELD_URL = 'url';

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
     * @var \Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Trigger\ProductEventTriggerInterface
     */
    protected $productEventTrigger;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface $urlFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface $urlGenerator
     * @param \Spryker\Zed\Product\Business\Product\Trigger\ProductEventTriggerInterface $productEventTrigger
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductToUrlInterface $urlFacade,
        ProductToTouchInterface $touchFacade,
        ProductToLocaleInterface $localeFacade,
        ProductQueryContainerInterface $productQueryContainer,
        ProductUrlGeneratorInterface $urlGenerator,
        ProductEventTriggerInterface $productEventTrigger,
        ProductRepositoryInterface $productRepository
    ) {
        $this->urlFacade = $urlFacade;
        $this->touchFacade = $touchFacade;
        $this->localeFacade = $localeFacade;
        $this->productQueryContainer = $productQueryContainer;
        $this->urlGenerator = $urlGenerator;
        $this->productEventTrigger = $productEventTrigger;
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productAbstractTransfer): ProductUrlTransfer {
            return $this->executeCreateProductUrlTransaction($productAbstractTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productAbstractTransfer): ProductUrlTransfer {
            return $this->executeUpdateProductUrlTransaction($productAbstractTransfer);
        });
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function updateProductUrls(array $productAbstractTransfers): array
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productAbstractTransfers): array {
            return $this->executeUpdateProductsUrlTransaction($productAbstractTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productUrl = new ProductUrlTransfer();
        $productUrl->setAbstractSku($productAbstractTransfer->getSku());

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
                $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale(),
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($productAbstractTransfer) {
            $this->executeDeleteProductUrlTransaction($productAbstractTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlActive(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($productAbstractTransfer) {
            $this->executeTouchProductAbstractUrlActiveTransaction($productAbstractTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlDeleted(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($productAbstractTransfer) {
            $this->executeTouchProductAbstractUrlDeletedTransaction($productAbstractTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    protected function executeCreateProductUrlTransaction(ProductAbstractTransfer $productAbstractTransfer): ProductUrlTransfer
    {
        $productUrl = $this->urlGenerator->generateProductUrl($productAbstractTransfer);

        foreach ($productUrl->getUrls() as $localizedUrlTransfer) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer
                ->setUrl($localizedUrlTransfer->getUrl())
                ->setFkLocale($localizedUrlTransfer->getLocale()->getIdLocale())
                ->setFkResourceProductAbstract($productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract());

            $this->urlFacade->createUrl($urlTransfer);
        }

        if ($productAbstractTransfer->getIdProductAbstract() !== null) {
            $this->productEventTrigger->triggerProductAbstractUpdateEvents([$productAbstractTransfer->getIdProductAbstract()]);
        }

        return $productUrl;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    protected function executeUpdateProductUrlTransaction(ProductAbstractTransfer $productAbstractTransfer): ProductUrlTransfer
    {
        $productUrl = $this->urlGenerator->generateProductUrl($productAbstractTransfer);

        foreach ($productUrl->getUrls() as $localizedUrlTransfer) {
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
                $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localizedUrlTransfer->getLocale()->getIdLocale(),
            );

            $urlTransfer
                ->setUrl($localizedUrlTransfer->getUrl())
                ->setFkLocale($localizedUrlTransfer->getLocale()->getIdLocale())
                ->setFkResourceProductAbstract($productAbstractTransfer->getIdProductAbstract());

            if ($urlTransfer->getIdUrl()) {
                $this->urlFacade->updateUrl($urlTransfer);
            } else {
                $this->urlFacade->createUrl($urlTransfer);
            }
        }

        return $productUrl;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    protected function executeUpdateProductsUrlTransaction(array $productAbstractTransfers): array
    {
        $productUrls = $this->urlGenerator->generateProductsUrl($productAbstractTransfers);

        $urlTransfers = $this->getUrlByProductAbstractIdsAndLocaleIds(
            array_map(function (ProductAbstractTransfer $productAbstractTransfer) {
                return $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract();
            }, $productAbstractTransfers),
            $productUrls,
        );

        return $this->urlFacade->saveUrlCollection($urlTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function executeDeleteProductUrlTransaction(ProductAbstractTransfer $productAbstractTransfer): void
    {
        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
                $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale(),
            );

            if ($urlTransfer->getIdUrl()) {
                $this->urlFacade->deleteUrl($urlTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function executeTouchProductAbstractUrlActiveTransaction(ProductAbstractTransfer $productAbstractTransfer): void
    {
        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
                $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale(),
            );

            $this->urlFacade->activateUrl($urlTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function executeTouchProductAbstractUrlDeletedTransaction(ProductAbstractTransfer $productAbstractTransfer): void
    {
        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlTransfer = $this->getUrlByIdProductAbstractAndIdLocale(
                $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
                $localeTransfer->getIdLocale(),
            );

            if (!$urlTransfer->getIdUrl()) {
                continue;
            }

            $this->urlFacade->deactivateUrl($urlTransfer);
        }
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
            ->findOne();

        if (!$urlEntity) {
            return (new UrlTransfer())
                ->setFkResourceProductAbstract($idProductAbstract)
                ->setFkLocale($idLocale);
        }

        $urlTransfer = (new UrlTransfer())
            ->fromArray($urlEntity->toArray(), true);

        return $urlTransfer;
    }

    /**
     * @param array<int> $productAbstractIds
     * @param array<\Generated\Shared\Transfer\ProductUrlTransfer> $productUrls
     *
     * @return array<string, \Generated\Shared\Transfer\UrlTransfer>
     */
    protected function getUrlByProductAbstractIdsAndLocaleIds(array $productAbstractIds, array $productUrls): array
    {
        $indexedUrlTransfers = $this->productRepository->getUrlsByProductAbstractIds(
            $productAbstractIds,
            fn (int $productAbstractId, int $localeId) => $this->generateIndex($productAbstractId, $localeId),
        );

        $urlTransfers = [];

        foreach ($productAbstractIds as $productAbstractId) {
            $urlTransfers = array_merge($urlTransfers, $this->processProductUrls($productAbstractId, $productUrls, $indexedUrlTransfers));
        }

        return $urlTransfers;
    }

    /**
     * @param int $productAbstractId
     * @param array<\Generated\Shared\Transfer\ProductUrlTransfer> $productUrls
     * @param array<string, \Generated\Shared\Transfer\UrlTransfer> $indexedUrlTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\UrlTransfer>
     */
    protected function processProductUrls(int $productAbstractId, array $productUrls, array $indexedUrlTransfers): array
    {
        $urlTransfers = [];
        foreach ($productUrls as $productUrlTransfer) {
            $urlTransfers = array_merge($urlTransfers, $this->processUrls($productAbstractId, $productUrlTransfer, $indexedUrlTransfers));
        }

        return $urlTransfers;
    }

    /**
     * @param int $productAbstractId
     * @param \Generated\Shared\Transfer\ProductUrlTransfer $productUrlTransfer
     * @param array<string, \Generated\Shared\Transfer\UrlTransfer> $indexedUrlTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\UrlTransfer>
     */
    protected function processUrls(int $productAbstractId, ProductUrlTransfer $productUrlTransfer, array $indexedUrlTransfers): array
    {
        $urlTransfers = [];
        foreach ($productUrlTransfer->getUrls() as $url) {
            $index = sprintf('%s-%s', $productAbstractId, $url->getLocale()->getIdLocale());
            $urlTransfer = $indexedUrlTransfers[$index] ?? (new UrlTransfer())
                ->setFkResourceProductAbstract($productAbstractId)
                ->setFkLocale($url->getLocale()->getIdLocale());
            if ($urlTransfer->getUrl() !== $url->getUrl()) {
                $urlTransfer->setOriginalUrl($urlTransfer->getUrl());
            }
            $urlTransfer = $urlTransfer->setUrl($url->getUrl());
            $urlTransfers[$url->getUrl()] = $urlTransfer;
        }

        return $urlTransfers;
    }

    /**
     * @param int $productAbstractId
     * @param int $localeId
     *
     * @return string
     */
    protected function generateIndex(int $productAbstractId, int $localeId): string
    {
        return sprintf('%s-%s', $productAbstractId, $localeId);
    }
}
