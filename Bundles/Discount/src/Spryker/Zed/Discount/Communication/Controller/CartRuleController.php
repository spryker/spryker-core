<?php

namespace Spryker\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\CartRuleTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function decisionRuleAction(Request $request)
    {
        $elements = $request->request->getInt(self::PARAM_CURRENT_ELEMENTS_COUNT);

        $form = $this->getFactory()->createDecisionRuleForm();
        $form->handleRequest($request);

        return [
            'form' => $form->createView(),
            'elementsCount' => $elements,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function collectorPluginsAction(Request $request)
    {
        $elements = $request->request->getInt(self::PARAM_CURRENT_ELEMENTS_COUNT);

        $form = $this->getFactory()->createCollectorPluginForm();
        $form->handleRequest($request);

        return [
            'form' => $form->createView(),
            'elementsCount' => $elements,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $form = $this->getFactory()->createCartRuleForm($this->getFacade());
        $form->handleRequest($request);

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
        $form = $this->getFactory()->createCartRuleForm($this->getFacade());
        $form->handleRequest($request);

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
