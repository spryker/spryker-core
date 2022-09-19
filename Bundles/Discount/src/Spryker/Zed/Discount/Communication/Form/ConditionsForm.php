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
use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 */
class ConditionsForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_DECISION_RULE_QUERY_STRING = 'decision_rule_query_string';

    /**
     * @var string
     */
    public const FIELD_MINIMUM_ITEM_AMOUNT = 'minimum_item_amount';

    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addDecisionRuleQueryString($builder);
        $this->addMinimumItemAmount($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_LOCALE => null,
        ]);
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
                    ],
                )->build(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return $this
     */
    protected function addMinimumItemAmount(FormBuilderInterface $builder, array $options)
    {
        $label = 'The discount can be applied if the query applies for at least X item(s).';

        $builder->add(static::FIELD_MINIMUM_ITEM_AMOUNT, FormattedNumberType::class, [
            'label' => $label,
            'locale' => $options[static::OPTION_LOCALE],
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
    public function getBlockPrefix(): string
    {
        return 'discount_conditions';
    }
}
