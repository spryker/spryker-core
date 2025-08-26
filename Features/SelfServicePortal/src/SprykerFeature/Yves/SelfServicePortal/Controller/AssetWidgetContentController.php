<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Generated\Shared\Transfer\SspAssetSearchCriteriaTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class AssetWidgetContentController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_SEARCH_STRING = 'searchString';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_PRODUCT = 'idProduct';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_CONTENT = 'content';

    public function indexAction(Request $request): JsonResponse
    {
        $assetWidgetContentViewData = $this->getAssetWidgetContentViewData($request);

        return $this->jsonResponse([
            static::RESPONSE_KEY_CONTENT => $this->renderView(
                '@SelfServicePortal/views/asset-widget-content/asset-widget-content.twig',
                $assetWidgetContentViewData,
            )->getContent(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function getAssetWidgetContentViewData(Request $request): array
    {
        $customerClient = $this->getFactory()->getCustomerClient();
        $assets = [];

        if ($customerClient->isLoggedIn()) {
            /** @var string $searchString */
            $searchString = $request->query->get(static::REQUEST_PARAM_SEARCH_STRING, '');
            $sspAssetSearchCollectionTransfer = $this->collectAssets($searchString);
            $assets = $sspAssetSearchCollectionTransfer->getSspAssets()->getArrayCopy();
        }

        return [
            'assets' => $assets,
            'idProduct' => $request->query->get(static::REQUEST_PARAM_ID_PRODUCT),
        ];
    }

    protected function collectAssets(string $searchString): SspAssetSearchCollectionTransfer
    {
        if (!$this->getFactory()->getCompanyUserClient()->findCompanyUser()) {
            return new SspAssetSearchCollectionTransfer();
        }

        return $this->getFactory()
            ->getSelfServicePortalClient()
            ->getSspAssetSearchCollection(
                (new SspAssetSearchCriteriaTransfer())
                    ->setSearchString($searchString),
            );
    }
}
