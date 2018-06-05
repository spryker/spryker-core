<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class MerchantForm extends AbstractType
{
    protected const FIELD_ID_MERCHANT = 'id_merchant';
    protected const FIELD_NAME = 'name';

    protected const LABEL_NAME = 'Name';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'merchant';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdMerchantField($builder)
            ->addNameField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdMerchantField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_MERCHANT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => static::LABEL_NAME,
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 255]),
        ];
    }
}
