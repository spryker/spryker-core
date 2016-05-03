<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\CartRuleTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainer getQueryContainer()
 */
class CartRuleController extends AbstractController
{

    const PARAM_ID_DISCOUNT = 'id-discount';
    const PARAM_CURRENT_ELEMENTS_COUNT = 'elements';

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createDiscountsTable();

        return [
            'discounts' => $table->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createDiscountsTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCartRuleFormDataProvider();
        $form = $this
            ->getFactory()
            ->createCartRuleForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $cartRuleFormTransfer = (new CartRuleTransfer())->fromArray($formData, true);
            $discount = $this->getFacade()->saveCartRules($cartRuleFormTransfer);

            return $this->redirectResponse('/discount/cart-rule/edit/?' . self::PARAM_ID_DISCOUNT . '=' . $discount->getIdDiscount());
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idDiscount = $this->castId($request->query->get(DiscountConstants::PARAM_ID_DISCOUNT));

        $dataProvider = $this->getFactory()->createCartRuleFormDataProvider();
        $form = $this
            ->getFactory()
            ->createCartRuleForm(
                $dataProvider->getData($idDiscount),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();
            $cartRuleFormTransfer = (new CartRuleTransfer())->fromArray($formData, true);
            $discount = $this->getFacade()->saveCartRules($cartRuleFormTransfer);

            return $this->redirectResponse('/discount/cart-rule/edit/?' . self::PARAM_ID_DISCOUNT . '=' . $discount->getIdDiscount());
        }

        return [
            'form' => $form->createView(),
        ];
    }

}
