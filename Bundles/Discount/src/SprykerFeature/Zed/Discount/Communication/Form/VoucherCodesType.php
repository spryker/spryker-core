<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class VoucherCodesType extends AbstractRuleType
{

    const NAME = 'name';
    const VOUCHER_POOL_CATEGORY = 'voucher_pool_category';
    const IS_INFINITELY_USABLE = 'is_infinitely_usable';
    const IS_ACTIVE = 'is_active';
    const IS_PRIVILEGED = 'is_privileged';
    const DESCRIPTION = 'description';
    const AMOUNT = 'amount';
    const AMOUNT_TYPE = 'type';
    const VALID_FROM = 'valid_from';
    const VALID_TO = 'valid_to';
    const DATE_NOW = 'now';
    const DATE_PERIOD_YEARS = 3;

    const FIELD_CALCULATOR_PLUGIN = 'calculator_plugin';
    const FIELD_COLLECTOR_PLUGIN = 'collector_plugin';
    const FIELD_DECISION_RULES = 'decision_rules';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::NAME, 'text', [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add(self::VOUCHER_POOL_CATEGORY, new AutosuggestType(), [
                'label' => 'Pool Category',
                'url' => '/discount/pool/category-suggest',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add(self::DESCRIPTION, 'textarea')
            ->add(self::AMOUNT, 'text', [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add(self::AMOUNT_TYPE, 'choice', [
                'label' => 'Value Type',
                'empty_value' => false,
                'choices' => [
                    SpyDiscountTableMap::COL_TYPE_FIXED => SpyDiscountTableMap::COL_TYPE_FIXED,
                    SpyDiscountTableMap::COL_TYPE_PERCENT => SpyDiscountTableMap::COL_TYPE_PERCENT,
                ]
            ])
            ->add(self::VALID_FROM, 'date', [
                'label' => 'Valid From',
            ])
            ->add(self::VALID_TO, 'date', [
                'label' => 'Valid Until'
            ])
            ->add(self::IS_INFINITELY_USABLE, 'checkbox', [
                'label' => 'Unlimited',
            ])
            ->add(self::IS_PRIVILEGED, 'checkbox', [
                'label' => 'Is Privileged',
            ])
            ->add(self::IS_ACTIVE, 'checkbox', [
                'label' => 'Active',
            ])
            ->add(self::FIELD_CALCULATOR_PLUGIN, 'choice', [
                'label' => 'Collector Plugin',
                'choices' => $this->getAvailableCalculatorPlugins(),
                'empty_data' => null,
                'required' => false,
                'placeholder' => 'Default',
            ])
            ->add(self::FIELD_COLLECTOR_PLUGIN, 'choice', [
                'label' => 'Collector Plugin',
                'choices' => $this->getAvailableCollectorPlugins(),
            ])
            ->add('group', 'text', [
                'data' => 'to be changed'
            ])
        ;
    }

    public function getName()
    {
        return 'voucher_codes';
    }

}
