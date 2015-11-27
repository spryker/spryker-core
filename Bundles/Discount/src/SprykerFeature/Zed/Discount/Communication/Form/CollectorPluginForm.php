<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use SprykerEngine\Zed\Gui\Communication\Form\NullFormTransfer;
use SprykerEngine\Shared\Transfer\TransferInterface;
use Symfony\Component\Form\FormBuilderInterface;

class CollectorPluginForm extends AbstractRuleForm
{

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

    public function populateFormFields()
    {
        return [];
    }

    /**
     * @return TransferInterface
     */
    protected function getDataClass()
    {
        //return new DiscountCollectorTransfer();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(DiscountCollectorTransfer::ID_DISCOUNT_COLLECTOR, 'hidden')
            ->add(DiscountCollectorTransfer::COLLECTOR_PLUGIN, 'choice', [
                'label' => 'Collector Plugin',
                'multiple' => false,
                'choices' => $this->getCollectorPluginsOptions(),
                'constraints' => [
                    $this->getConstraints()->createConstraintRequired(),
                ],
            ])
            ->add(DiscountCollectorTransfer::VALUE, 'text', [
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
