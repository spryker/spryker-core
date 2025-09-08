<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\PaginationTransfer;
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
    protected const REQUEST_PARAM_OFFSET = 'offset';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_LIMIT = 'limit';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_ASSET_REFERENCE = 'sspAssetReference';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_CONTENT = 'content';

    /**
     * @var string
     */
    protected const ROUTE_NAME_SEARCH = 'self-service-portal/asset-widget-content';

    /**
     * @var int
     */
    protected const DEFAULT_OFFSET = 0;

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
        $nbResults = 0;

        if (!$customerClient->isLoggedIn()) {
            return [
                'assets' => [],
            ];
        }

        $searchString = (string)$request->query->get(static::REQUEST_PARAM_SEARCH_STRING, '');
        $offset = (int)$request->query->get(static::REQUEST_PARAM_OFFSET, static::DEFAULT_OFFSET);
        $limit = (int)$request->query->get(static::REQUEST_PARAM_LIMIT, $this->getFactory()->getConfig()->getSspAssetSearchPaginationConfigTransfer()->getDefaultItemsPerPage());
        $sspAssetSearchCollectionTransfer = $this->getSspAssets($searchString, $offset, $limit);
        $sspAssets = $sspAssetSearchCollectionTransfer->getSspAssets()->getArrayCopy();
        $nbResults = $sspAssetSearchCollectionTransfer->getNbResults() ?? count($sspAssets);

        $sspAssetReference = $request->query->get(static::REQUEST_PARAM_ASSET_REFERENCE);

        return [
            'assets' => $sspAssets,
            'idProduct' => $request->query->get(static::REQUEST_PARAM_ID_PRODUCT),
            'offset' => $offset,
            'limit' => $limit,
            'nbResults' => $nbResults,
            'searchRoute' => static::ROUTE_NAME_SEARCH,
            'searchString' => $searchString,
            'assetReference' => $sspAssetReference,
        ];
    }

    protected function getSspAssets(string $searchString, int $offset, int $limit): SspAssetSearchCollectionTransfer
    {
        if (!$this->getFactory()->getCompanyUserClient()->findCompanyUser()) {
            return new SspAssetSearchCollectionTransfer();
        }

        return $this->getFactory()
            ->getSelfServicePortalClient()
            ->getSspAssetSearchCollection(
                (new SspAssetSearchCriteriaTransfer())
                    ->setSearchString($searchString)
                    ->setPagination(
                        (new PaginationTransfer())
                            ->setOffset($offset)
                            ->setLimit($limit),
                    ),
            );
    }
}
