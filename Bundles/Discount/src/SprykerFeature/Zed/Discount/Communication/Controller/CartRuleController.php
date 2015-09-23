<?php

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\Form\CartRuleForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CartRuleController extends AbstractController
{
    const PARAM_ID_DISCOUNT = 'id-discount';

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
     * @return array|RedirectResponse
     */
    public function createAction()
    {
        /** @var CartRuleForm $form */
        $form = $this->getDependencyContainer()->createCartRuleForm(0);
        $form->handleRequest();

        if ($form->isValid()) {
            $formData = $form->getData();

            $discountTransfer = (new DiscountTransfer())->fromArray($formData, true);
            $discountTransfer->setCollectorPlugin('default');

            $discount = $this->getFacade()->createDiscount($discountTransfer);

            $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($formData, true);
            $decisionRuleTransfer->setFkDiscount($discount->getIdDiscount());
            $decisionRuleTransfer->setName($discount->getDisplayName());

            $this->getFacade()->createDiscountDecisionRule($decisionRuleTransfer);

            return $this->redirectResponse('/discount/cart-rule/edit/?' . self::PARAM_ID_DISCOUNT . '=' . $discount->getIdDiscount());
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idDiscount = $request->query->getInt(self::PARAM_ID_DISCOUNT);

        /** @var CartRuleForm $form */
        $form = $this->getDependencyContainer()->createCartRuleForm($idDiscount);
        $form->handleRequest();

        if ($form->isValid()) {
            $formData = $form->getData();
            $this->updateDiscount($formData, $idDiscount);

            return $this->redirectResponse('/discount/cart-rule/edit/?' . self::PARAM_ID_DISCOUNT . '=' . $idDiscount);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**`
     * @param array $formData
     * @param int $idDiscount
     */
    protected function updateDiscount(array $formData, $idDiscount)
    {
        $discountEntity = $this->getQueryContainer()->queryDiscount()->findOneByIdDiscount($idDiscount);

        $discountTransfer = (new DiscountTransfer())->fromArray($discountEntity->toArray(), true);
        $discountTransfer->fromArray($formData, true);

        $this->getFacade()->updateDiscount($discountTransfer);

        $this->updateDecisionRule($discountTransfer, $formData, $idDiscount);
    }

    /**
     * @param DiscountTransfer $discountTransfer
     * @param array $formData
     * @param int $idDiscount
     */
    protected function updateDecisionRule(DiscountTransfer $discountTransfer, array $formData, $idDiscount)
    {
        $decisionRuleEntity = $this->getQueryContainer()->queryDecisionRules($idDiscount)->findOne();

        $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($decisionRuleEntity->toArray(), true);
        $decisionRuleTransfer->fromArray($formData, true);
        $decisionRuleTransfer->setName($discountTransfer->getDisplayName());

        $this->getFacade()->updateDiscountDecisionRule($decisionRuleTransfer);
    }

}
