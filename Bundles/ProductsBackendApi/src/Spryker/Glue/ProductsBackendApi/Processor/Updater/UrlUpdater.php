<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Updater;

use ArrayObject;
use Generated\Shared\Transfer\UrlCollectionTransfer;
use Generated\Shared\Transfer\UrlConditionsTransfer;
use Generated\Shared\Transfer\UrlCriteriaTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToUrlFacadeInterface;

class UrlUpdater implements UrlUpdaterInterface
{
    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToUrlFacadeInterface
     */
    protected ProductsBackendApiToUrlFacadeInterface $urlFacade;

    /**
     * @var \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface
     */
    protected ProductsBackendApiToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToUrlFacadeInterface $urlFacade
     * @param \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductsBackendApiToUrlFacadeInterface $urlFacade,
        ProductsBackendApiToLocaleFacadeInterface $localeFacade
    ) {
        $this->urlFacade = $urlFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductUrlsBackendApiAttributesTransfer> $productUrlsBackendApiAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\UrlCollectionTransfer
     */
    public function validateUrlsOnCreate(ArrayObject $productUrlsBackendApiAttributesTransfers): UrlCollectionTransfer
    {
        $urlMapping = [];
        $urlConditionsTransfer = new UrlConditionsTransfer();
        foreach ($productUrlsBackendApiAttributesTransfers as $productUrlsBackendApiAttributesTransfer) {
            $localeTransfer = $this->localeFacade->getLocale($productUrlsBackendApiAttributesTransfer->getLocaleOrFail());

            $urlMapping[$localeTransfer->getIdLocale()] = $productUrlsBackendApiAttributesTransfer;

            $urlConditionsTransfer->addIdLocale($localeTransfer->getIdLocaleOrFail());
            $urlConditionsTransfer->addUrl($productUrlsBackendApiAttributesTransfer->getUrlOrFail());
        }
        $urlCriteriaTransfer = (new UrlCriteriaTransfer())->setUrlConditions($urlConditionsTransfer);
        $urlCollectionTransfer = $this->urlFacade->getUrlCollection($urlCriteriaTransfer);

        return $urlCollectionTransfer;
    }

    /**
     * @param int $idProductAbstract
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductUrlsBackendApiAttributesTransfer> $productUrlsBackendApiAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\UrlCollectionTransfer
     */
    public function validateUrlsOnUpdate(int $idProductAbstract, ArrayObject $productUrlsBackendApiAttributesTransfers): UrlCollectionTransfer
    {
        $urlMapping = [];
        $urlConditionsTransfer = (new UrlConditionsTransfer())->setResourceProductAbstractIds([$idProductAbstract]);
        foreach ($productUrlsBackendApiAttributesTransfers as $productUrlsBackendApiAttributesTransfer) {
            $localeTransfer = $this->localeFacade->getLocale($productUrlsBackendApiAttributesTransfer->getLocaleOrFail());

            $urlMapping[$localeTransfer->getIdLocale()] = $productUrlsBackendApiAttributesTransfer;

            $urlConditionsTransfer->addIdLocale($localeTransfer->getIdLocaleOrFail());
            $urlConditionsTransfer->addNotResourceProductAbstractId($idProductAbstract);
            $urlConditionsTransfer->addUrl($productUrlsBackendApiAttributesTransfer->getUrlOrFail());
        }
        $urlCriteriaTransfer = (new UrlCriteriaTransfer())->setUrlConditions($urlConditionsTransfer);
        $urlCollectionTransfer = $this->urlFacade->getUrlCollection($urlCriteriaTransfer);

        return $urlCollectionTransfer;
    }

    /**
     * @param int $idProductAbstract
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductUrlsBackendApiAttributesTransfer> $productUrlsBackendApiAttributesTransfers
     *
     * @return void
     */
    public function createUrls(int $idProductAbstract, ArrayObject $productUrlsBackendApiAttributesTransfers): void
    {
        foreach ($productUrlsBackendApiAttributesTransfers as $productUrlsBackendApiAttributesTransfer) {
            $localeTransfer = $this->localeFacade->getLocale($productUrlsBackendApiAttributesTransfer->getLocaleOrFail());
            $urlTransfer = (new UrlTransfer())
                ->setUrl($productUrlsBackendApiAttributesTransfer->getUrl())
                ->setFkResourceProductAbstract($idProductAbstract)
                ->setFkLocale($localeTransfer->getIdLocale());

            $this->urlFacade->createUrl($urlTransfer);
        }
    }

    /**
     * @param int $idProductAbstract
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductUrlsBackendApiAttributesTransfer> $productUrlsBackendApiAttributesTransfers
     *
     * @return void
     */
    public function updateUrls(int $idProductAbstract, ArrayObject $productUrlsBackendApiAttributesTransfers): void
    {
        $urlMapping = [];
        $urlConditionsTransfer = (new UrlConditionsTransfer())->setResourceProductAbstractIds([$idProductAbstract]);
        foreach ($productUrlsBackendApiAttributesTransfers as $productUrlsBackendApiAttributesTransfer) {
            $localeTransfer = $this->localeFacade->getLocale($productUrlsBackendApiAttributesTransfer->getLocaleOrFail());

            $urlMapping[$localeTransfer->getIdLocale()] = $productUrlsBackendApiAttributesTransfer;

            $urlConditionsTransfer->addIdLocale($localeTransfer->getIdLocaleOrFail());
            $urlConditionsTransfer->addIdResourceProductAbstract($idProductAbstract);
        }
        $urlCriteriaTransfer = (new UrlCriteriaTransfer())->setUrlConditions($urlConditionsTransfer);
        $urlCollectionTransfer = $this->urlFacade->getUrlCollection($urlCriteriaTransfer);

        foreach ($urlCollectionTransfer->getUrls() as $persistedUrlTransfer) {
            $productUrlsBackendApiAttributesTransfer = $urlMapping[$persistedUrlTransfer->getFkLocale()];
            $persistedUrlTransfer->fromArray($productUrlsBackendApiAttributesTransfer->modifiedToArray(), true);
            $this->urlFacade->updateUrl($persistedUrlTransfer);
        }
    }
}
