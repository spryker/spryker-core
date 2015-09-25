<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class DecisionRuleType extends AbstractRuleType
{

    const FIELD_DECISION_RULE_PLUGIN = 'decision_rule_plugin';
    const FIELD_VALUE = 'value';

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
            ->add(self::FIELD_DECISION_RULE_PLUGIN, 'choice', [
                'label' => 'Decision Rule',
                'multiple' => false,
                'choices' => $this->getDecisionRuleOptions(),
                'constraints' => [
                    new Required(),
                ],
            ])
            ->add(self::FIELD_VALUE, 'text', [
                'label' => 'Amount',
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan([
                        'value' => 0,
                    ])
                ]
            ])
        ;
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
