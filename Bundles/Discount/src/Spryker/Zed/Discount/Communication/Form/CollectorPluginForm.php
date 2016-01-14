<?php

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Zed\Discount\DiscountConfig;
use Symfony\Component\Form\FormBuilderInterface;

class CollectorPluginForm extends AbstractRuleForm
{

    const FIELD_ID_DISCOUNT_COLLECTOR = 'id_discount_collector';
    const FIELD_COLLECTOR_PLUGIN = 'collector_plugin';
    const FIELD_VALUE = 'value';
    const FIELD_REMOVE = 'remove';

    /**
     * @param DiscountConfig $config
     */
    public function __construct(DiscountConfig $config)
    {
        parent::__construct(
            $config->getAvailableCalculatorPlugins(),
            $config->getAvailableCollectorPlugins(),
            $config->getAvailableDecisionRulePlugins()
        );
    }

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
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_ID_DISCOUNT_COLLECTOR, 'hidden')
            ->add(self::FIELD_COLLECTOR_PLUGIN, 'choice', [
                'label' => 'Collector Plugin',
                'multiple' => false,
                'choices' => $this->getAvailableCollectorPlugins(),
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
     * @return string
     */
    public function getName()
    {
        return 'collector_plugin';
    }

}
