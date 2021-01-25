<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\MerchantUserGui\Communication\MerchantUserGuiCommunicationFactory getFactory()
 */
class MerchantUserCreateForm extends AbstractType
{
    public const FIELD_USERNAME = 'username';
    public const FIELD_FIRST_NAME = 'firstName';
    public const FIELD_LAST_NAME = 'lastName';
    public const FIELD_STATUS = 'status';

    protected const GROUP_UNIQUE_USERNAME_CHECK = 'unique_email_check';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'merchant-user';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addEmailField($builder)
            ->addFirstNameField($builder)
            ->addLastNameField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_USERNAME, TextType::class, [
                'label' => 'E-mail',
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    $this->getFactory()->createUniqueEmailConstraint(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFirstNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FIRST_NAME, TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLastNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LAST_NAME, TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        return $this;
    }
}
