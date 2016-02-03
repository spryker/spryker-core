<?php

namespace Spryker\Zed\Discount\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;

class DecisionRuleForm extends AbstractRuleForm
{

    const FIELD_DECISION_RULE_PLUGIN = 'decision_rule_plugin';
    const FIELD_VALUE = 'value';
    const FIELD_REMOVE = 'remove';
    const FIELD_ID_DISCOUNT_DECISION_RULE = 'id_discount_decision_rule';

    /**
     * @return array
     */
    public function populateFormFields()
    {
        return [];
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_ID_DISCOUNT_DECISION_RULE, 'hidden')
            ->add(self::FIELD_DECISION_RULE_PLUGIN, 'choice', [
                'label' => 'Decision Rule',
                'multiple' => false,
                'choices' => $this->getAvailableDecisionRulePlugins(),
            ])
            ->add(self::FIELD_VALUE, 'text', [
                'label' => 'Value',
            ]);
        $builder->add(self::FIELD_REMOVE, 'button', [
            'attr' => [
                'class' => 'btn btn-xs btn-danger remove-form-collection',
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'decision_rule';
    }

}
