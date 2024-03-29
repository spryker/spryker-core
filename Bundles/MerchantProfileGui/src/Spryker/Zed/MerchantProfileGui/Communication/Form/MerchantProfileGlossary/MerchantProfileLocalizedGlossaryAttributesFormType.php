<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileGlossary;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 */
class MerchantProfileLocalizedGlossaryAttributesFormType extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_MERCHANT_PROFILE_GLOSSARY_ATTRIBUTES_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const FIELD_MERCHANT_PROFILE_GLOSSARY_ATTRIBUTES = 'merchantProfileGlossaryAttributeValues';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMerchantProfileGlossaryAttributeLocaleField($builder)
            ->addMerchantProfileGlossaryAttributeValuesSubform($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantProfileGlossaryAttributeLocaleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_MERCHANT_PROFILE_GLOSSARY_ATTRIBUTES_LOCALE, MerchantProfileGlossaryAttributeLocaleFormType::class, [
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
}
