<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class PublishController extends AbstractController
{
    public const URL_IMPORT_PAGE = 'price-product-schedule-gui/import';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $priceProductScheduleListTransfer = (new PriceProductScheduleListTransfer())
            ->setIdPriceProductScheduleList(
                $request->query->getInt(PriceProductScheduleListTransfer::ID_PRICE_PRODUCT_SCHEDULE_LIST)
            );

        $priceProductScheduleListResponseTransfer = $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->findPriceProductScheduleList($priceProductScheduleListTransfer);

        if ($priceProductScheduleListResponseTransfer->getIsSuccess() === false) {
            $this->addErrorMessages($priceProductScheduleListResponseTransfer->getErrors());

            return $this->redirectResponse(static::URL_IMPORT_PAGE);
        }

        $priceProductScheduleListTransfer = $priceProductScheduleListResponseTransfer->getPriceProductScheduleList();
        $priceProductScheduleListTransfer->setIsActive(true);

        $this->getFactory()
            ->getPriceProductScheduleFacade()
            ->updatePriceProductScheduleList($priceProductScheduleListTransfer);

        return $this->viewResponse();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductScheduleListErrorTransfer[] $priceProductScheduleListErrorTransfers
     *
     * @return void
     */
    protected function addErrorMessages(ArrayObject $priceProductScheduleListErrorTransfers): void
    {
        foreach ($priceProductScheduleListErrorTransfers as $priceProductScheduleListErrorTransfer) {
            $this->addErrorMessage($priceProductScheduleListErrorTransfer->getMessage());
        }
    }
}
