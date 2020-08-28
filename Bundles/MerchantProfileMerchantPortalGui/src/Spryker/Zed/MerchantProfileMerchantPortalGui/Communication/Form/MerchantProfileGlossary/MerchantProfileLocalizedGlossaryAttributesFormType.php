<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\MerchantProfileGlossary;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantProfileLocalizedGlossaryAttributesFormType extends AbstractType
{
    protected const FIELD_MERCHANT_PROFILE_GLOSSARY_ATTRIBUTES = 'merchantProfileGlossaryAttributeValues';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMerchantProfileGlossaryAttributeValuesSubform($builder);
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
