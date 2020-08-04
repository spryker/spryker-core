<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\Communication\MerchantSalesOrderGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrderGui\Business\MerchantSalesOrderGuiFacadeInterface getFacade()
 */
class DetailController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_SUB_REQUEST
     */
    protected const SERVICE_SUB_REQUEST = 'sub_request';

    protected const MASSAGE_MERCHANT_ORDER_EXIST = 'Merchant order doesn\'t exist.';

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $idMerchantSalesOrder = $this->castId($request->query->getInt(MerchantSalesOrderGuiConfig::REQUEST_ID_MERCHANT_SALES_ORDER));
        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchant();

        $merchantOrderTransfer = $this->getFactory()->getMerchantSalesOrderFacade()->findMerchantOrder(
            (new MerchantOrderCriteriaTransfer())
                ->setIdMerchantOrder($idMerchantSalesOrder)
                ->setIdMerchant($idMerchant)
                ->setWithItems(true)
                ->setWithOrder(true)
        );
        if (!$merchantOrderTransfer) {
            throw new AccessDeniedHttpException(static::MASSAGE_MERCHANT_ORDER_EXIST);
        }

        $blockData = $this->renderMultipleActions(
            $request,
            $this->getFactory()->getMerchantSalesOrderDetailExternalBlocksUrls(),
            $merchantOrderTransfer
        );

        return [
            'merchantOrder' => $merchantOrderTransfer,
            'totalMerchantOrderCount' => $this->getFactory()->getMerchantSalesOrderFacade()->getMerchantOrdersCount(
                (new MerchantOrderCriteriaTransfer())->setMerchantReference($merchantOrderTransfer->getMerchantReference())
            ),
            'blocks' => $blockData,
        ];
    }

    /**
     * @phpstan-param array <string, string> $data
     *
     * @phpstan-return array <string, string>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return array
     */
    protected function renderMultipleActions(Request $request, array $data, MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $subRequest = clone $request;
        $subRequest->setMethod(Request::METHOD_POST);

        /** @var array $merchantOrderTransfer */
        $subRequest->request->set('merchantOrderTransfer', $merchantOrderTransfer);

        $responseData = [];
        foreach ($data as $blockName => $blockUrl) {
            $responseData[$blockName] = $this->handleSubRequest($subRequest, $blockUrl);
        }

        return $responseData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $blockUrl
     *
     * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleSubRequest(Request $request, $blockUrl)
    {
        $blockResponse = $this->getApplication()->get(static::SERVICE_SUB_REQUEST)->handleSubRequest($request, $blockUrl);
        if ($blockResponse instanceof RedirectResponse) {
            return $blockResponse;
        }

        return $blockResponse->getContent();
    }
}
