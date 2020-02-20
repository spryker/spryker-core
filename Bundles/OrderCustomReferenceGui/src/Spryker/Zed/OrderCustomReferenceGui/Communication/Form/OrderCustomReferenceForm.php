<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @method \Spryker\Zed\OrderCustomReferenceGui\Communication\OrderCustomReferenceGuiCommunicationFactory getFactory()
 */
class OrderCustomReferenceForm extends AbstractType
{
    protected const FIELD_ORDER_CUSTOM_REFERENCE = 'order-custom-reference';
    protected const FIELD_ID_SALES_ORDER = 'id-sales-order';
    protected const FIELD_BACK_URL = 'back-url';
    protected const FIELD_SAVE = 'save';
    protected const FIELD_CANCEL = 'cancel';

    protected const ROUTE_ORDER_CUSTOM_REFERENCE_SAVE = '/order-custom-reference-gui/sales/save';

    protected const ORDER_CUSTOM_REFERENCE_MAX_LENGTH = 255;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAction(static::ROUTE_ORDER_CUSTOM_REFERENCE_SAVE);

        $this->addOrderCustomReferenceField($builder);
        $builder->add(static::FIELD_ID_SALES_ORDER, HiddenType::class);
        $builder->add(static::FIELD_BACK_URL, HiddenType::class);

        $this->addSaveButton($builder);
        $builder->add(static::FIELD_CANCEL, ButtonType::class);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderCustomReferenceField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_ORDER_CUSTOM_REFERENCE,
            TextType::class,
            [
                'label' => 'Order Custom Reference',
                'constraints' => [
                    new Length(['max' => static::ORDER_CUSTOM_REFERENCE_MAX_LENGTH]),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSaveButton(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_SAVE,
            SubmitType::class,
            [
                'attr' => [
                    'class' => 'btn btn-primary safe-submit',
                ],
            ]
        );

        return $this;
    }
}
