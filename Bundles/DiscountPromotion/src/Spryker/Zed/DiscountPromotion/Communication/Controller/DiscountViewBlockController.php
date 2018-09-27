<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 */
class DiscountViewBlockController extends AbstractController
{
    public const URL_PARAM_ID_DISCOUNT = 'id-discount';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idDiscount = $this->castId($request->request->get(static::URL_PARAM_ID_DISCOUNT));

        $discountPromotionTransfer = $this->getFacade()
            ->findDiscountPromotionByIdDiscount($idDiscount);

        return [
            'discountPromotion' => $discountPromotionTransfer,
        ];
    }
}
