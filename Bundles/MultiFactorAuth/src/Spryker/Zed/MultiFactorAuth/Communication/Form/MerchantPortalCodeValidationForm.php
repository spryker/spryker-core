<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 */
class MerchantPortalCodeValidationForm extends CodeValidationForm
{
    /**
     * @var string
     */
    protected const FIELD_FORM_SELECTOR = 'form_selector';

    /**
     * @var string
     */
    protected const FIELD_AJAX_FORM_SELECTOR = 'ajax_form_selector';

    /**
     * @var string
     */
    protected const FIELD_IS_LOGIN = 'is_login';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::FIELD_FORM_SELECTOR => null,
            static::FIELD_AJAX_FORM_SELECTOR => null,
            static::FIELD_IS_LOGIN => false,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addFormSelectorHiddenField($builder, $options);
        $this->addAjaxFormSelectorHiddenField($builder, $options);
        $this->addIsLoginHiddenField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFormSelectorHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FORM_SELECTOR, HiddenType::class, [
            'data' => $options[static::FIELD_FORM_SELECTOR],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAjaxFormSelectorHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_AJAX_FORM_SELECTOR, HiddenType::class, [
            'data' => $options[static::FIELD_AJAX_FORM_SELECTOR],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIsLoginHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_IS_LOGIN, HiddenType::class, [
            'data' => $options[static::FIELD_IS_LOGIN],
        ]);

        return $this;
    }
}
