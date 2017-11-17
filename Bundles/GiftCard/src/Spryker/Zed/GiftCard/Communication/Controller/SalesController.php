<?php


namespace Spryker\Zed\GiftCard\Communication\Controller;


use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        /** @var OrderTransfer $orderTransfer */
        $orderTransfer = $request->request->get('orderTransfer');

        $giftCardTransfers = $this->getFacade()->findGiftCardsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        return [
            'giftCards' => $giftCardTransfers,
            'order' => $orderTransfer,
        ];
    }
}