<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementFactory getFactory()
 */
class ServicePointWidgetContentController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_SKU = 'sku';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_QUANTITY = 'quantity';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_SERVICE_TYPE_KEY = 'service-type-key';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_SERVICE_TYPE_UUID = 'service-type-uuid';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_SHIPMENT_TYPE_UUID = 'shipment-type-uuid';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_CONTENT = 'content';

    /**
     * @var string
     */
    protected const VIEW_DATA_KEY_SERVICE_TYPE_KEY = 'serviceTypeKey';

    /**
     * @var string
     */
    protected const VIEW_DATA_KEY_SERVICE_TYPE_UUID = 'serviceTypeUuid';

    /**
     * @var string
     */
    protected const VIEW_DATA_KEY_SHIPMENT_TYPE_UUID = 'shipmentTypeUuid';

    /**
     * @var string
     */
    protected const VIEW_DATA_KEY_ITEMS = 'items';

    /**
     * @var string
     */
    protected const VIEW_DATA_KEY_SKU = 'sku';

    /**
     * @var string
     */
    protected const VIEW_DATA_KEY_QUANTITY = 'quantity';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $servicePointWidgetContentViewData = $this->getServicePointWidgetContentViewData($request);

        return $this->jsonResponse([
            static::RESPONSE_KEY_CONTENT => $this->renderServicePointWidgetContent($servicePointWidgetContentViewData),
        ]);
    }

    /**
     * @param array<string, mixed> $servicePointWidgetContentViewData
     *
     * @return string
     */
    protected function renderServicePointWidgetContent(array $servicePointWidgetContentViewData): string
    {
        $response = $this->renderView(
            $this->getFactory()->getConfig()->getServicePointWidgetContentTemplatePath(),
            $servicePointWidgetContentViewData,
        );

        return $response->getContent() ?: '';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function getServicePointWidgetContentViewData(Request $request): array
    {
        /** @var int $quantity */
        $quantity = $request->query->get(static::REQUEST_PARAM_QUANTITY, 1);

         /** @var string $sku */
        $sku = $request->query->get(static::REQUEST_PARAM_SKU);

        $itemTransfers = [
            (new ItemTransfer())
            ->setSkuOrFail($sku)
            ->setQuantity($quantity)
            ->setIsMerchantCheckSkipped(true),
        ];

        return [
            static::VIEW_DATA_KEY_SERVICE_TYPE_KEY => $request->query->get(static::REQUEST_PARAM_SERVICE_TYPE_KEY),
            static::VIEW_DATA_KEY_SERVICE_TYPE_UUID => $request->query->get(static::REQUEST_PARAM_SERVICE_TYPE_UUID),
            static::VIEW_DATA_KEY_SHIPMENT_TYPE_UUID => $request->query->get(static::REQUEST_PARAM_SHIPMENT_TYPE_UUID),
            static::VIEW_DATA_KEY_ITEMS => $itemTransfers,
            static::VIEW_DATA_KEY_SKU => $sku,
            static::VIEW_DATA_KEY_QUANTITY => $quantity,
        ];
    }
}
