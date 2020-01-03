<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Form\DoubleSubmitProtection\Type;

use Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface;
use Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface;
use Spryker\Shared\Form\DoubleSubmitProtection\Subscriber\FormEventSubscriber;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class DoubleSubmitFormType extends AbstractTypeExtension
{
    protected const OPTION_KEY_ERROR_MESSAGE = 'double_submit_error';
    protected const OPTION_KEY_TOKEN_FIELD_NAME = 'token_field_name';

    protected const DEFAULT_TOKEN_FIELD_NAME = '_requestToken';
    protected const DEFAULT_ERROR_MESSAGE = 'This form has been already submitted.';

    /**
     * @var \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $tokenProvider
     */
    protected $tokenGenerator;

    /**
     * @var \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $storage
     */
    protected $storage;

    /**
     * @var string $fieldName
     */
    protected $fieldName;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface|null
     */
    protected $translator;

    /**
     * @var string|null
     */
    protected $translationDomain;

    /**
     * @param \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $tokenGenerator
     * @param \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $storage
     * @param \Symfony\Component\Translation\TranslatorInterface|null $translator
     * @param string|null $translationDomain
     */
    public function __construct(
        TokenGeneratorInterface $tokenGenerator,
        StorageInterface $storage,
        ?TranslatorInterface $translator = null,
        ?string $translationDomain = null
    ) {
        $this->tokenGenerator = $tokenGenerator;
        $this->storage = $storage;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $eventSubscriber = $this->createFormEventSubscriber();

        $builder->addEventSubscriber($eventSubscriber);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view The form view
     * @param \Symfony\Component\Form\FormInterface $form The form
     * @param array $options The options
     *
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if ($view->parent || !$form->isRoot() || !isset($options[static::OPTION_KEY_TOKEN_FIELD_NAME])) {
            return;
        }

        $factory = $form->getConfig()->getFormFactory();
        $token = $this->tokenGenerator->generateToken();
        $fieldName = $options[static::OPTION_KEY_TOKEN_FIELD_NAME];
        $formName = $form->getName() ?: get_class($form->getConfig()->getType()->getInnerType());
        $this->storage->setToken($formName, $token);

        $tokenForm = $factory->createNamed(
            $fieldName,
            HiddenType::class,
            $token,
            ['mapped' => false]
        );

        $view->children[$fieldName] = $tokenForm->createView($view);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            static::OPTION_KEY_ERROR_MESSAGE => static::DEFAULT_ERROR_MESSAGE,
            static::OPTION_KEY_TOKEN_FIELD_NAME => static::DEFAULT_TOKEN_FIELD_NAME,
        ]);
    }

    /**
     * @return string
     */
    public function getExtendedType(): string
    {
        return FormType::class;
    }

    /**
     * @return \Spryker\Shared\Form\DoubleSubmitProtection\Subscriber\FormEventSubscriber
     */
    protected function createFormEventSubscriber(): FormEventSubscriber
    {
        return new FormEventSubscriber(
            $this->tokenGenerator,
            $this->storage,
            static::DEFAULT_TOKEN_FIELD_NAME,
            static::DEFAULT_ERROR_MESSAGE,
            $this->translator,
            $this->translationDomain
        );
    }
}
