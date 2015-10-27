<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;

class DecisionRuleType extends AbstractRuleType
{

    const FIELD_DECISION_RULE_PLUGIN = 'decision_rule_plugin';
    const FIELD_VALUE = 'value';
    const FIELD_REMOVE = 'remove';
    const FIELD_ID_DISCOUNT_DECISION_RULE = 'id_discount_decision_rule';

    /**
     * @var array
     */
    protected $availableDecisionRulePlugins;

    /**
     * DecisionRuleType constructor.
     *
     * @param array $availableDecisionRulePlugins
     */
    public function __construct(array $availableDecisionRulePlugins)
    {
        $this->availableDecisionRulePlugins = $availableDecisionRulePlugins;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_ID_DISCOUNT_DECISION_RULE, 'hidden')
            ->add(self::FIELD_DECISION_RULE_PLUGIN, 'choice', [
                'label' => 'Decision Rule',
                'multiple' => false,
                'choices' => $this->getDecisionRuleOptions(),
            ])
            ->add(self::FIELD_VALUE, 'text', [
                'label' => 'Value',
            ])
        ;
        $builder->add(self::FIELD_REMOVE, 'button', [
            'attr' => [
                'class' => 'btn btn-xs btn-danger remove-form-collection',
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function getDecisionRuleOptions()
    {
        $decisionRules = [];
        $decisionRulesKeys = array_keys($this->availableDecisionRulePlugins);

        foreach ($decisionRulesKeys as $key) {
            $decisionRules[$key] = $this->filterChoicesLabels($key);
        }

        return $decisionRules;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'decision_rule';
    }

}
