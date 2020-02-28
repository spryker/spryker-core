<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\OrderCustomReferenceGui\Communication\OrderCustomReferenceGuiCommunicationFactory getFactory()
 */
class OrderCustomReferenceForm extends AbstractType
{
    public const FIELD_ORDER_CUSTOM_REFERENCE = 'orderCustomReference';
    public const FIELD_ID_SALES_ORDER = 'idSalesOrder';
    public const FIELD_BACK_URL = 'backUrl';

    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH = 'order_custom_reference.validation.error.message_invalid_length';

    protected const ORDER_CUSTOM_REFERENCE_MAX_LENGTH = 255;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addOrderCustomReferenceField($builder)
            ->addBackUrlField($builder)
            ->addIdSalesOrderField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderCustomReferenceField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ORDER_CUSTOM_REFERENCE,
            TextType::class,
            [
                'label' => 'Custom Order Reference',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => static::ORDER_CUSTOM_REFERENCE_MAX_LENGTH,
                        'maxMessage' => static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH,
                    ]),
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
    protected function addIdSalesOrderField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ID_SALES_ORDER,
            HiddenType::class,
            [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
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
    protected function addBackUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_BACK_URL, HiddenType::class);

        return $this;
    }
}
