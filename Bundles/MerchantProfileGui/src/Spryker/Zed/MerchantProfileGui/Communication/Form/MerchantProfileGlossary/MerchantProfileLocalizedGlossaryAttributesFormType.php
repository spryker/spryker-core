<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileGlossary;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 */
class MerchantProfileLocalizedGlossaryAttributesFormType extends AbstractType
{
    protected const FIELD_FK_LOCALE = 'fkLocale';
    protected const FIELD_LOCALE_NAME = 'localeName';
    protected const FIELD_MERCHANT_PROFILE_GLOSSARY_ATTRIBUTES = 'merchantProfileGlossaryAttributeValues';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addFkLocaleField($builder)
            ->addLocaleNameField($builder)
            ->addMerchantProfileGlossaryAttributeValuesSubform($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkLocaleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_LOCALE, HiddenType::class, [
            'label' => false,
        ]);

        $builder->get(static::FIELD_FK_LOCALE)->addModelTransformer($this->createStringToNumberTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocaleNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALE_NAME, HiddenType::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantProfileGlossaryAttributeValuesSubform(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_MERCHANT_PROFILE_GLOSSARY_ATTRIBUTES, MerchantProfileGlossaryAttributeValuesFormType::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createStringToNumberTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                return (string)$value;
            },
            function ($value) {
                return (int)$value;
            }
        );
    }
}
