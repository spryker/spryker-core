<?php

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\CartRuleTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
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

        $formType = $this->getDependencyContainer()->createDecisionRuleFormType();

        $form = $this->getDependencyContainer()->createCartRuleForm($formType);
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

        $formType = $this->getDependencyContainer()->createCollectorPluginFormType();

        $form = $this->getDependencyContainer()->createCartRuleForm($formType);
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
        $form = $this->getDependencyContainer()->createCartRuleForm(
            $this->getDependencyContainer()->createCartRuleFormType()
        );
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
        $idDiscount = $request->query->getInt(self::PARAM_ID_DISCOUNT);

        $form = $this->getDependencyContainer()->createCartRuleForm(
            $this->getDependencyContainer()->createCartRuleFormType(),
            $this->getFacade()->getCurrentCartRulesDetailsByIdDiscount($idDiscount)
        );
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
