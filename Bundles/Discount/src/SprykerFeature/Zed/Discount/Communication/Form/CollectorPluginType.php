<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class CollectorPluginType extends AbstractRuleType
{

    const FIELD_COLLECTOR_PLUGIN = 'collector_plugin';
    const FIELD_VALUE = 'value';

    /**
     * @var array
     */
    protected $availableCollectorPlugins;

    /**
     * DecisionRuleType constructor.
     *
     * @param array $availableCollectorPlugins
     */
    public function __construct(array $availableCollectorPlugins)
    {
        $this->availableCollectorPlugins = $availableCollectorPlugins;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_COLLECTOR_PLUGIN, 'choice', [
                'label' => 'Collector Plugin',
                'multiple' => false,
                'choices' => $this->getCollectorPluginsOptions(),
                'constraints' => [
                    new Required(),
                ],
            ])
            ->add(self::FIELD_VALUE, 'text', [
                'label' => 'Value',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
        ;
    }

    /**
     * @return array
     */
    protected function getCollectorPluginsOptions()
    {
        $decisionRules = [];
        $decisionRulesKeys = array_keys($this->availableCollectorPlugins);

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
