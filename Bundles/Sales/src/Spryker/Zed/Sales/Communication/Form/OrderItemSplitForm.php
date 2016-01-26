<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Form;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderItemSplitForm extends AbstractForm
{

    const FIELD_QUANTITY = 'quantity';
    const FIELD_ID_ORDER_ITEM = 'id_order_item';
    const FIELD_ID_ORDER = 'id_order';
    const VALIDATE_MESSAGE_NUMERIC = 'Please provide numeric value.';
    const VALIDATION_MESSAGE_QUANTITY = 'Please provide quantity.';

    /**
     * @var SpySalesOrderItem
     */
    protected $orderItem;

    /**
     * @param SpySalesOrderItem $orderItem
     */
    public function __construct(SpySalesOrderItem $orderItem = null)
    {
        $this->orderItem = $orderItem;
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'order_item_split';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_QUANTITY, 'text', [
                'label' => 'Quantity',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank([
                        'message' => self::VALIDATION_MESSAGE_QUANTITY,
                    ]),
                    $this->getConstraints()->createConstraintRegex([
                        'pattern' => '/^\d+$/',
                        'message' => self::VALIDATE_MESSAGE_NUMERIC,
                    ]),
                ],
            ])
            ->add(self::FIELD_ID_ORDER_ITEM, 'hidden')
            ->add(self::FIELD_ID_ORDER, 'hidden')
            ->add('Split', 'submit', [
                'attr' => [
                    'class' => 'btn btn-sm btn-primary',
                ],
            ]);
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        if ($this->orderItem === null) {
            return [];
        }

        return [
            self::FIELD_ID_ORDER_ITEM => $this->orderItem->getIdSalesOrderItem(),
            self::FIELD_ID_ORDER => $this->orderItem->getFkSalesOrder(),
        ];
    }

}
