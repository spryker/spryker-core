<?php

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\Form\CartRuleType;
use SprykerFeature\Zed\Discount\Communication\Form\DecisionRuleType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

        $discountConfig = $this->getDependencyContainer()->getConfig();
        $formType = new DecisionRuleType($discountConfig);

        $form = $this->getDependencyContainer()->createSymfonyForm($formType, [
            'csrf_protection' => false,
        ]);
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
        $discountConfig = $this->getDependencyContainer()->getConfig();
        $formType = new CartRuleType($discountConfig);

        $form = $this->getDependencyContainer()->createSymfonyForm($formType);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $discountTransfer = (new DiscountTransfer())->fromArray($formData, true);

            $discount = $this->getFacade()->createDiscount($discountTransfer);

            foreach ($formData[CartRuleType::FIELD_CART_RULES] as $cartRules) {
                $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($cartRules, true);
                $decisionRuleTransfer->setFkDiscount($discount->getIdDiscount());
                $decisionRuleTransfer->setName($discount->getDisplayName());

                $this->getDependencyContainer()->saveDiscountDecisionRule($decisionRuleTransfer);

            }
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

        $discountConfig = $this->getDependencyContainer()->getConfig();
        $formType = new CartRuleType($discountConfig);

        $form = $this->getDependencyContainer()->createSymfonyForm(
            $formType,
            $this->getDependencyContainer()->getCurrentCartRulesDetailsByIdDiscount($idDiscount)
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $discountTransfer = (new DiscountTransfer())->fromArray($formData, true);
            $discount = $this->getFacade()->updateDiscount($discountTransfer);

            foreach ($formData[CartRuleType::FIELD_CART_RULES] as $cartRules) {
                $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($cartRules, true);
                $decisionRuleTransfer->setFkDiscount($discount->getIdDiscount());
                $decisionRuleTransfer->setName($discount->getDisplayName());

                $this->getDependencyContainer()->saveDiscountDecisionRule($decisionRuleTransfer);
            }

            return $this->redirectResponse('/discount/cart-rule/edit/?' . self::PARAM_ID_DISCOUNT . '=' . $discount->getIdDiscount());
        }

        return [
            'form' => $form->createView(),
        ];
    }

}
