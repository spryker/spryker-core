<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\GiftCardBalance\Communication\GiftCardBalanceCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    const PARAM_ID_GIFT_CARD = 'id-gift-card';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idGiftCard = $request->get(static::PARAM_ID_GIFT_CARD);

        $table = $this->getFactory()
            ->createGiftCardBalanceTable($idGiftCard);

        return $this->viewResponse([
            'giftCardBalanceTable' => $table->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idGiftCard = $request->get(static::PARAM_ID_GIFT_CARD);

        $table = $this->getFactory()
            ->createGiftCardBalanceTable($idGiftCard);

        return $this->jsonResponse($table->fetchData());
    }
}
