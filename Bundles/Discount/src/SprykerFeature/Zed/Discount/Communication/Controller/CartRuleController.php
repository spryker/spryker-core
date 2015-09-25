<?php

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\Form\CartRuleType;
use SprykerFeature\Zed\Discount\Communication\Form\DecisionRuleType;
use SprykerFeature\Zed\Discount\DiscountDependencyProvider;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CartRuleController extends AbstractController
{
    const PARAM_ID_DISCOUNT = 'id-discount';
    const PARAM_CURRENT_ELEMENTS_COUNT = 'elements';

    const CART_RULES_ITERATOR = 'rule_';
    const ITERATOR_FIRST = 1;

    /**
     * @var array
     */
    protected $dateTypeFields = [
        'valid_from',
        'valid_to',
        'created_at',
        'updated_at',
    ];

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
     * @todo CD-474 refactor Form Generator
     *
     * @param array|null $defaultData
     *
     * @return FormInterface
     */
    protected function createSymfonyForm(FormTypeInterface $form, array $defaultData = null)
    {
        if (null === $defaultData) {
            $defaultData = [
                'cart_rules' => [
                    self::CART_RULES_ITERATOR . self::ITERATOR_FIRST => [
                        'value' => '',
                        'rules' => '',
                    ],
                ]
            ];
        }

        /** @var FormFactory $formFactory */
        $formFactory = $this->getApplication()['form.factory'];

        return $formFactory->create($form, $defaultData, [
            'allow_extra_fields' => true,
        ]);
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
        $formType = new DecisionRuleType($discountConfig, 'cart_rule[cart_rules][rule_' . $elements . ']');

        $form = $this->createSymfonyForm($formType, [
            'csrf_protection' => false,
        ]);
        $form->handleRequest($request);

        return [
            'form' => $form->createView(),
            'elementsCount' => $elements,
        ];

    }

    /**
     * @todo CD-474 refactor Form Generator
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $discountConfig = $this->getDependencyContainer()->getConfig();
        $formType = new CartRuleType($discountConfig);

        $form = $this->createSymfonyForm($formType);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $discountTransfer = (new DiscountTransfer())->fromArray($formData, true);

            $discount = $this->getFacade()->createDiscount($discountTransfer);

            foreach ($formData[CartRuleType::FIELD_CART_RULES] as $cartRules) {
                $decisionRuleTransfer = (new DecisionRuleTransfer())->fromArray($cartRules, true);
                $decisionRuleTransfer->setFkDiscount($discount->getIdDiscount());
                $decisionRuleTransfer->setName($discount->getDisplayName());

                $this->getFacade()->createDiscountDecisionRule($decisionRuleTransfer);
            }
            return $this->redirectResponse('/discount/cart-rule/edit/?' . self::PARAM_ID_DISCOUNT . '=' . $discount->getIdDiscount());
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @todo CD-474 refactor Form Generator
     *
     * @param Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {
        $idDiscount = $request->query->getInt(self::PARAM_ID_DISCOUNT);

        $discountConfig = $this->getDependencyContainer()->getConfig();
        $formType = new CartRuleType($discountConfig);

        $form = $this->createSymfonyForm(
            $formType,
            $this->getCurrentCartRulesDetailsByIdDiscount($idDiscount)
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
                if (null === $decisionRuleTransfer->getIdDiscountDecisionRule()) {
                    $this->getFacade()->createDiscountDecisionRule($decisionRuleTransfer);
                    continue;
                }
                $this->getFacade()->updateDiscountDecisionRule($decisionRuleTransfer);
            }
            return $this->redirectResponse('/discount/cart-rule/edit/?' . self::PARAM_ID_DISCOUNT . '=' . $discount->getIdDiscount());
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @todo CD-474 refactor Form Generator
     *
     * @param $idDiscount
     *
     * @return array
     */
    protected function getCurrentCartRulesDetailsByIdDiscount($idDiscount)
    {
        $discount = $this->getQueryContainer()->queryDiscount()->findOneByIdDiscount($idDiscount);

        $defaultData = $this->fixDateFormats($discount->toArray());

        /** @var ObjectCollection $rules */
        $rules = $this->getQueryContainer()->queryDecisionRules($idDiscount)->find();

        if ($rules->count() > 0) {
            $i = self::ITERATOR_FIRST;
            foreach ($rules as $decisionRule) {
                $defaultData[CartRuleType::FIELD_CART_RULES][self::CART_RULES_ITERATOR . $i] = $this->fixDateFormats($decisionRule->toArray());
                $i++;
            }
        }

        return $defaultData;
    }

    /**
     * @todo CD-474 refactor Form Generator
     *
     * @param array $entityArray
     *
     * @return array
     */
    protected function fixDateFormats(array $entityArray)
    {
        $store = $this->getDependencyContainer()
            ->getProvidedDependency(DiscountDependencyProvider::STORE_CONFIG)
        ;

        foreach ($entityArray as $key => &$value) {
            if (in_array($key, $this->dateTypeFields)) {
                if (!($value instanceof \DateTime)) {
                    $value = \DateTime::createFromFormat('Y-m-d\TG:i:s\Z', $value, new \DateTimeZone($store->getTimezone()));
                }
            }
        }

        return $entityArray;
    }

}
