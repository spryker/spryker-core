<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;

class CollectorPluginForm extends AbstractRuleForm
{

    const FIELD_ID_DISCOUNT_COLLECTOR = 'id_discount_collector';
    const FIELD_COLLECTOR_PLUGIN = 'collector_plugin';
    const FIELD_VALUE = 'value';
    const FIELD_REMOVE = 'remove';

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
     * @return array
     */
    public function populateFormFields()
    {
        return [];
    }

    /**
     * @return void
     */
    protected function getDataClass()
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_ID_DISCOUNT_COLLECTOR, 'hidden')
            ->add(self::FIELD_COLLECTOR_PLUGIN, 'choice', [
                'label' => 'Collector Plugin',
                'multiple' => false,
                'choices' => $this->getCollectorPluginsOptions(),
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                ],
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
