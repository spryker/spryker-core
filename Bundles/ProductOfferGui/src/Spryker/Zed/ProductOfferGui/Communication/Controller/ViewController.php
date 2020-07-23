<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductOfferGui\ProductOfferGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferGui\Communication\ProductOfferGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiRepositoryInterface getRepository()
 */
class ViewController extends AbstractController
{
    protected const PARAM_ID_PRODUCT_OFFER = 'id-product-offer';

    /**
     * @phpstan-return array<mixed>|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductOffer = $this->castId($request->get(
            self::PARAM_ID_PRODUCT_OFFER
        ));

        $productOfferCriteriaFilter = (new ProductOfferCriteriaFilterTransfer())
            ->setIdProductOffer($idProductOffer);

        $productOfferTransfer = $this->getFactory()
            ->getProductOfferFacade()
            ->findOne($productOfferCriteriaFilter);

        if (!$productOfferTransfer) {
            return $this->redirectResponse(ProductOfferGuiConfig::URL_PRODUCT_OFFER_LIST);
        }

        $storeNames = $this->getStoreNames($productOfferTransfer->getStores());

        $applicableProductOfferStatuses = $this->getFactory()
            ->getProductOfferFacade()
            ->getApplicableMerchantStatuses($productOfferTransfer->getApprovalStatus());

        $productConcreteTransfer = $this->getFactory()
            ->getProductFacade()
            ->getProductConcrete($productOfferTransfer->getConcreteSku());

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setSku($productConcreteTransfer->getAbstractSku());

        $isGiftCard = $this->getFactory()
            ->createProductTypeHelper()
            ->isGiftCardByProductAbstract($productAbstractTransfer);

        $isProductBundle = $this->getFactory()
            ->createProductTypeHelper()
            ->isProductBundleByProductAbstract($productAbstractTransfer);

        return $this->viewResponse([
            'applicableProductOfferStatuses' => $applicableProductOfferStatuses,
            'productConcrete' => $productConcreteTransfer,
            'productOffer' => $productOfferTransfer,
            'localeCollection' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'relatedStoreNames' => $storeNames,
            'viewPlugins' => $this->getViewPlugins($productOfferTransfer),
            'isGiftCard' => $isGiftCard,
            'isProductBundle' => $isProductBundle,
        ]);
    }

    /**
     * @phpstan-param \ArrayObject<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return string[]
     */
    protected function getStoreNames(ArrayObject $storeTransfers): array
    {
        $storeNames = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeNames[] = $storeTransfer->getName();
        }

        return $storeNames;
    }

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    protected function getViewPlugins(ProductOfferTransfer $productOfferTransfer): array
    {
        $viewPlugins = [];

        foreach ($this->getFactory()->getProductOfferViewSectionPlugins() as $productOfferViewSectionPlugin) {
            $viewPlugins[$productOfferViewSectionPlugin->getTemplate()] = $productOfferViewSectionPlugin->getData($productOfferTransfer);
        }

        return $viewPlugins;
    }
}
