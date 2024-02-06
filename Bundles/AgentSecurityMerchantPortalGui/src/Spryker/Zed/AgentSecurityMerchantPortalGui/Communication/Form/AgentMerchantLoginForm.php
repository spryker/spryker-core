<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig getConfig()
 */
class AgentMerchantLoginForm extends AbstractType
{
    /**
     * @var string
     */
    public const FORM_NAME = 'agent-security-merchant-portal-gui';

    /**
     * @var string
     */
    public const FIELD_USERNAME = 'username';

    /**
     * @var string
     */
    public const FIELD_PASSWORD = 'password';

    /**
     * @var string
     */
    public const BUTTON_LOGIN = 'login';

    /**
     * @var string
     */
    protected const LABEL_USERNAME = 'Email';

    /**
     * @var string
     */
    protected const LABEL_PASSWORD = 'Password';

    /**
     * @var string
     */
    protected const LABEL_LOGIN = 'Login';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAction($this->getConfig()->getUrlLoginCheck());

        $this->addUserNameField($builder)
            ->addPasswordField($builder)
            ->addLoginButton($builder);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addUserNameField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_USERNAME,
            EmailType::class,
            [
                'label' => static::LABEL_USERNAME,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
                'attr' => [
                    'placeholder' => 'Email Address',
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addPasswordField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PASSWORD,
            PasswordType::class,
            [
                'label' => static::LABEL_PASSWORD,
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'placeholder' => 'Password',
                    'autocomplete' => 'off',
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addLoginButton(FormBuilderInterface $builder)
    {
        $builder->add(static::BUTTON_LOGIN, SubmitType::class, [
            'label' => static::LABEL_LOGIN,
        ]);

        return $this;
    }
}
