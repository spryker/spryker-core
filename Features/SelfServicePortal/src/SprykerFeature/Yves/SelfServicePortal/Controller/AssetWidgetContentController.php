<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
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
            $customerTransfer = $customerClient->getCustomer();

            if ($customerTransfer && $customerTransfer->getCompanyUserTransfer()) {
                $searchString = $request->query->get(static::REQUEST_PARAM_SEARCH_STRING, '');
                /**
                 * @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
                 */
                $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

                $sspAssetCriteriaTransfer = $this->createSspAssetCriteriaTransfer((string)$searchString);
                $sspAssetCollectionTransfer = $this->getFactory()
                    ->createSspAssetReader()
                    ->getSspAssetCollection($request, $sspAssetCriteriaTransfer, $companyUserTransfer);

                $assets = $sspAssetCollectionTransfer->getSspAssets();
            }
        }

        return [
            'assets' => $assets,
        ];
    }

    protected function createSspAssetCriteriaTransfer(string $searchString): SspAssetCriteriaTransfer
    {
        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();
        $sspAssetConditionsTransfer = new SspAssetConditionsTransfer();

        if ($searchString !== '') {
            $sspAssetConditionsTransfer->setSearchText($searchString);
        }

        return $sspAssetCriteriaTransfer->setSspAssetConditions($sspAssetConditionsTransfer);
    }
}
