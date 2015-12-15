<?php

namespace Spryker\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\CartRuleTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Communication\DiscountDependencyContainer;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 * @method DiscountFacade getFacade()
 * @method DiscountQueryContainer getQueryContainer()
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
        $table = $this->getDependencyContainer()->createDiscountsTable();

        return [
            'discounts' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createDiscountsTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function decisionRuleAction(Request $request)
    {
        $elements = $request->request->getInt(self::PARAM_CURRENT_ELEMENTS_COUNT);

        $form = $this->getDependencyContainer()->createDecisionRuleForm();
        $form->handleRequest($request);

        return [
            'form' => $form->createView(),
            'elementsCount' => $elements,
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function collectorPluginsAction(Request $request)
    {
        $elements = $request->request->getInt(self::PARAM_CURRENT_ELEMENTS_COUNT);

        $form = $this->getDependencyContainer()->createCollectorPluginForm();
        $form->handleRequest($request);

        return [
            'form' => $form->createView(),
            'elementsCount' => $elements,
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createCartRuleForm($this->getFacade());
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
     * @param Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $form = $this->getDependencyContainer()->createCartRuleForm();
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
