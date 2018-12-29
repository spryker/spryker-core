<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class ShippingController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAddressAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->query->get(SalesConfig::PARAM_ID_SALES_ORDER));
        $idShippingAddress = $this->castId($request->query->get('id-shipping-address'));

        $orderTransfer = $this->getFacade()->findOrderByIdSalesOrder($idSalesOrder);

        if ($orderTransfer === null) {
            $this->addErrorMessage(sprintf(
                'Sales order #%d not found.',
                $idSalesOrder
            ));

            return $this->redirectResponse(Url::generate('/sales')->build());
        }

        $dataProvider = $this->getFactory()->createCorrectShippingAddressFormDataProvider();
        $form = $this->getFactory()
            ->getAddressForm(
                $dataProvider->getData($idShippingAddress),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressTransfer = (new AddressTransfer())->fromArray($form->getData(), true);
            $addressTransfer->setIdSalesOrderAddress($idShippingAddress);
            $this->getFacade()
                ->updateOrderAddress($addressTransfer, $idShippingAddress);

            $this->addSuccessMessage('Address successfully updated.');

            return $this->redirectResponse(
                Url::generate(
                    '/sales/detail',
                    [
                        SalesConfig::PARAM_ID_SALES_ORDER => $idSalesOrder,
                    ]
                )->build()
            );
        }

        return $this->viewResponse([
            'idSalesOrder' => $idSalesOrder,
            'order' => $orderTransfer,
            'addressForm' => $form->createView(),
            'eventsGroupedByItem' => [],
        ]);
    }
}
