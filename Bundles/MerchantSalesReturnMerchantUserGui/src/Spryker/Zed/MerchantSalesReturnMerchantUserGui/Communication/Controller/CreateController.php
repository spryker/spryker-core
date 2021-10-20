<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\MerchantSalesReturnMerchantUserGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_MERCHANT_ORDER = 'id-merchant-order';

    /**
     * @var string
     */
    protected const PARAM_ID_RETURN = 'id-return';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\IndexController::indexAction()
     * @var string
     */
    protected const ROUTE_RETURN_LIST = '/merchant-sales-return-merchant-user-gui';

    /**
     * @uses \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Controller\DetailController::indexAction()
     * @var string
     */
    protected const ROUTE_RETURN_DETAIL = '/merchant-sales-return-merchant-user-gui/detail';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_NOT_FOUND_ERROR = 'Merchant for current user not found.';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_ORDER_NOT_FOUND_ERROR = 'Merchant sales order #%d not found.';

    /**
     * @var string
     */
    protected const MESSAGE_PARAM_ID = '%id%';

    /**
     * @var string
     */
    protected const MESSAGE_RETURN_CREATED_SUCCESS = 'Return was successfully created.';

    /**
     * @var string
     */
    protected const MESSAGE_RETURN_CREATE_FAIL = 'Return has not been created.';

    /**
     * @phpstan-return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchant();

        if (!$idMerchant) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_NOT_FOUND_ERROR);
            $redirectUrl = Url::generate(static::ROUTE_RETURN_LIST)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $idMerchantOrder = $this->castId($request->get(static::PARAM_ID_MERCHANT_ORDER));
        $merchantOrderTransfer = $this->findMerchantOrder($idMerchantOrder, $idMerchant);

        if (!$merchantOrderTransfer) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_ORDER_NOT_FOUND_ERROR, [
                static::MESSAGE_PARAM_ID => $idMerchantOrder,
            ]);

            return $this->redirectResponse(
                Url::generate(static::ROUTE_RETURN_LIST)->build()
            );
        }

        $merchantOrderTransfer->setMerchantOrderItems(
            new ArrayObject($this->getMerchantOrderItems($merchantOrderTransfer))
        );

        $orderTransfer = $merchantOrderTransfer->getOrderOrFail();

        $returnCreateForm = $this->getFactory()
            ->createReturnCreateForm($orderTransfer)
            ->handleRequest($request);

        if ($returnCreateForm->isSubmitted() && $returnCreateForm->isValid()) {
            return $this->processReturnCreateForm($returnCreateForm, $orderTransfer);
        }

        return [
            'returnCreateForm' => $returnCreateForm->createView(),
            'merchantOrder' => $merchantOrderTransfer,
        ];
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $returnCreateForm
     *
     * @phpstan-return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     *
     * @param \Symfony\Component\Form\FormInterface $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function processReturnCreateForm(FormInterface $returnCreateForm, OrderTransfer $orderTransfer)
    {
        $returnResponseTransfer = $this->getFactory()
            ->createCreateReturnFormHandler()
            ->handleForm($returnCreateForm, $orderTransfer);

        if ($returnResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_RETURN_CREATED_SUCCESS);

            $redirectUrl = Url::generate(static::ROUTE_RETURN_DETAIL, [
                static::PARAM_ID_RETURN => $returnResponseTransfer->getReturnOrFail()->getIdSalesReturn(),
            ]);

            return $this->redirectResponse($redirectUrl);
        }

        $this->addErrorMessage(static::MESSAGE_RETURN_CREATE_FAIL);

        return [
            'returnCreateForm' => $returnCreateForm->createView(),
            'order' => $orderTransfer,
        ];
    }

    /**
     * @param int $idMerchantOrder
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    protected function findMerchantOrder(int $idMerchantOrder, int $idMerchant): ?MerchantOrderTransfer
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdMerchantOrder($idMerchantOrder)
            ->setIdMerchant($idMerchant)
            ->setWithItems(true)
            ->setWithOrder(true);

        return $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->findMerchantOrder($merchantOrderCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantOrderItemTransfer>
     */
    protected function getMerchantOrderItems(MerchantOrderTransfer $merchantOrderTransfer): array
    {
        $merchantOrderItemCriteriaTransfer = new MerchantOrderItemCriteriaTransfer();

        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItem) {
            $merchantOrderItemCriteriaTransfer->addMerchantOrderItemId(
                $merchantOrderItem->getIdMerchantOrderItemOrFail()
            );
        }

        return $this->getFactory()
            ->createMerchantOrderReader()
            ->getMerchantOrderItems($merchantOrderItemCriteriaTransfer);
    }
}
