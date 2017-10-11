<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Discount\Business\DiscountFacadeInterface;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory;
use Spryker\Zed\Discount\Communication\Form\Constraint\QueryString;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConditionsForm extends AbstractType
{

    const FIELD_DECISION_RULE_QUERY_STRING = 'decision_rule_query_string';

    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacadeInterface $discountFacade
     */
    public function __construct(DiscountFacadeInterface $discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addDecisionRuleQueryString($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDecisionRuleQueryString(FormBuilderInterface $builder)
    {
        $label = 'Apply when';

        $builder->add(static::FIELD_DECISION_RULE_QUERY_STRING, 'textarea', [
            'label' => $label,
            'constraints' => [
                new QueryString([
                    QueryString::OPTION_DISCOUNT_FACADE => $this->discountFacade,
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
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'discount_conditions';
    }

}
