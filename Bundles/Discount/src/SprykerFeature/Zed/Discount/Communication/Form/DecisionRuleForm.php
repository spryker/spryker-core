<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Dependency\Facade\DiscountFacadeInterface;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class DecisionRuleForm extends AbstractForm
{

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @param Request $request
     * @param QueryContainerInterface $queryContainer
     * @param DiscountFacadeInterface $discountFacade
     */
    public function __construct(
        Request $request,
        QueryContainerInterface $queryContainer,
        DiscountFacadeInterface $discountFacade
    ) {
        $this->discountFacade = $discountFacade;
        parent::__construct($request, $queryContainer);
    }

    /**
     * @return array|Field[]
     */
    public function addFormFields()
    {
        $this->addField('id_discount_decision_rule')
            ->setConstraints([
                new Assert\Optional([
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ]),
            ]);

        $this->addField('fk_discount')
            ->setAccepts($this->getDiscounts())
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getDiscounts(), 'value'),
                    'message' => 'Please choose one of the given Discounts',
                ]),
                new Assert\NotNull(),
            ]);

        $this->addField('name')
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);

        $this->addField('decision_rule_plugin')
            ->setRefresh(false)
            ->setAccepts($this->getDecisionRulePlugins())
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getDecisionRulePlugins(), 'value'),
                    'message' => 'Please choose one of the given Plugins',
                ]),
                new Assert\NotNull(),
            ]);

        $this->addField('value')
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $idDiscountDecisionRule = $this->stateContainer->getRequestValue('id_discount_decision_rule');
        $discountDecisionRuleEntity = $this->queryContainer
            ->queryDiscountDecisionRule()
            ->findPk($idDiscountDecisionRule);

        if ($discountDecisionRuleEntity) {
            return $discountDecisionRuleEntity->toArray();
        }

        return [];
    }

    /**
     * @return array
     */
    protected function getDiscounts()
    {
        $discounts = $this->queryContainer->queryDiscount()->find();

        $data = [];
        /** @var SpyDiscount $discount */
        foreach ($discounts as $discount) {
            $data[] = [
                'value' => $discount->getPrimaryKey(),
                'label' => $discount->getDisplayName(),
            ];
        }

        if (empty($data)) {
            $data[] = [
                'value' => '',
                'label' => '',
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getDecisionRulePlugins()
    {
        $decisionRulePluginNames = $this->discountFacade->getDecisionRulePluginNames();

        $data = [];
        foreach ($decisionRulePluginNames as $name) {
            $data[] = [
                'value' => $name,
                'label' => $name,
            ];
        }

        return $data;
    }

}
