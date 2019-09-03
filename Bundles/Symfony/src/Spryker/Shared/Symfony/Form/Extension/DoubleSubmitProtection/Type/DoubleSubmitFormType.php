<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\Type;

use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\FormEventSubscriber;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface;
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
    public const OPTION_KEY_ERROR_MESSAGE = 'double_submit_error';
    public const OPTION_KEY_TOKEN_FIELD_NAME = 'token_field_name';

    public const DEFAULT_TOKEN_FIELD_NAME = '_requestToken';
    public const DEFAULT_ERROR_MESSAGE = 'This form has been already submitted.';

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $tokenProvider
     */
    protected $tokenGenerator;

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $storage
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
     * @param \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $tokenGenerator
     * @param \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $storage
     * @param \Symfony\Component\Translation\TranslatorInterface|null $translator
     * @param string|null $translationDomain
     */
    public function __construct(
        TokenGeneratorInterface $tokenGenerator,
        StorageInterface $storage,
        ?TranslatorInterface $translator = null,
        $translationDomain = null
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $eventSubscriber = $this->createFormEventSubscriber();

        $eventSubscriber
            ->setErrorMessage($options[static::OPTION_KEY_ERROR_MESSAGE])
            ->setFieldName($options[static::OPTION_KEY_TOKEN_FIELD_NAME]);

        $builder->addEventSubscriber($eventSubscriber);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view The form view
     * @param \Symfony\Component\Form\FormInterface $form The form
     * @param array $options The options
     *
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($view->parent || !$form->isRoot()) {
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                static::OPTION_KEY_ERROR_MESSAGE => static::DEFAULT_ERROR_MESSAGE,
                static::OPTION_KEY_TOKEN_FIELD_NAME => static::DEFAULT_TOKEN_FIELD_NAME,
            ]
        );
    }

    /**
     * @deprecated Use `configureOptions()` instead.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * @return string
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

    /**
     * @return \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\FormEventSubscriber
     */
    protected function createFormEventSubscriber()
    {
        return new FormEventSubscriber(
            $this->tokenGenerator,
            $this->storage,
            $this->translator,
            $this->translationDomain
        );
    }
}
