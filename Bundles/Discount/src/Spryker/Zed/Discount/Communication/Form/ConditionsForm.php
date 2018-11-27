<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory;
use Spryker\Zed\Discount\Communication\Form\Constraint\QueryString;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 */
class ConditionsForm extends AbstractType
{
    public const FIELD_DECISION_RULE_QUERY_STRING = 'decision_rule_query_string';
    public const FIELD_MINIMUM_ITEM_AMOUNT = 'minimum_item_amount';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addDecisionRuleQueryString($builder);
        $this->addMinimumItemAmount($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDecisionRuleQueryString(FormBuilderInterface $builder)
    {
        $label = 'Apply when';

        $builder->add(static::FIELD_DECISION_RULE_QUERY_STRING, TextareaType::class, [
            'label' => $label,
            'constraints' => [
                new QueryString([
                    QueryString::OPTION_DISCOUNT_FACADE => $this->getFacade(),
                    QueryString::OPTION_QUERY_STRING_TYPE => MetaProviderFactory::TYPE_DECISION_RULE,
                ]),
            ],
            'attr' => [
                'data-label' => $label,
                'data-url' => Url::generate(
                    '/discount/query-string/rule-fields',
                    [
                        'type' => MetaProviderFactory::TYPE_DECISION_RULE,
                    ]
                )->build(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMinimumItemAmount(FormBuilderInterface $builder): self
    {
        $label = 'The discount can be applied if the query applies for at least X item(s).';

        $builder->add(static::FIELD_MINIMUM_ITEM_AMOUNT, NumberType::class, [
            'label' => $label,
            'constraints' => [
                new NotBlank(),
                new GreaterThanOrEqual(DiscountConfig::DEFAULT_MINIMUM_ITEM_AMOUNT),
            ],
            'attr' => [
                'min' => DiscountConfig::DEFAULT_MINIMUM_ITEM_AMOUNT,
            ],
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'discount_conditions';
    }
}
