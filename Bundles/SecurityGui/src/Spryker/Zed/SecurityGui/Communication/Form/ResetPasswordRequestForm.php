<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityGui\Business\SecurityGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SecurityGui\SecurityGuiConfig getConfig()
 */
class ResetPasswordRequestForm extends AbstractType
{
    public const FIELD_EMAIL = 'email';
    public const FIELD_SUBMIT = 'submit';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEmailField($builder)
            ->addSubmitField($builder);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'reset_password';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_EMAIL, TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
                'attr' => [
                    'placeholder' => 'Email Address',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubmitField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SUBMIT, SubmitType::class, [
                'label' => 'Recover password',
                'attr' => [
                    'class' => 'btn btn-success btn-block btn-outline',
                ],
            ]);

        return $this;
    }
}
