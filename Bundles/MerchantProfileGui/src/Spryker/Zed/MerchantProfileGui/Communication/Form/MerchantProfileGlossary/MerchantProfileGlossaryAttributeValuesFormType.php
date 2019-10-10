<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileGlossary;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 */
class MerchantProfileGlossaryAttributeValuesFormType extends AbstractType
{
    protected const FIELD_DESCRIPTION_GLOSSARY = 'descriptionGlossaryKey';
    protected const FIELD_BANNER_URL_GLOSSARY = 'bannerUrlGlossaryKey';
    protected const FIELD_DELIVERY_TIME_GLOSSARY = 'deliveryTimeGlossaryKey';
    protected const FIELD_TERMS_CONDITIONS_GLOSSARY = 'termsConditionsGlossaryKey';
    protected const FIELD_CANCELLATION_POLICY_GLOSSARY = 'cancellationPolicyGlossaryKey';
    protected const FIELD_IMPRINT_GLOSSARY = 'imprintGlossaryKey';
    protected const FIELD_DATA_PRIVACY_GLOSSARY = 'dataPrivacyGlossaryKey';

    protected const LABEL_DESCRIPTION_GLOSSARY = 'Description';
    protected const LABEL_BANNER_URL_GLOSSARY = 'Banner URL';
    protected const LABEL_DELIVERY_TIME_GLOSSARY = 'Average Delivery Time';
    protected const LABEL_TERMS_CONDITIONS_GLOSSARY = 'Terms and Conditions';
    protected const LABEL_CANCELLATION_POLICY_GLOSSARY = 'Cancellation Policy';
    protected const LABEL_IMPRINT_GLOSSARY = 'Imprint';
    protected const LABEL_DATA_PRIVACY_GLOSSARY = 'Data Privacy';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addDescriptionGlossaryKeyField($builder)
            ->addBannerUrlGlossaryKeyField($builder)
            ->addDeliveryTimeGlossaryKeyField($builder)
            ->addTermsConditionsGlossaryKeyField($builder)
            ->addCancellationPolicyGlossaryKeyField($builder)
            ->addImprintGlossaryKeyField($builder)
            ->addDataPrivacyGlossaryKeyField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DESCRIPTION_GLOSSARY, TextType::class, [
            'label' => static::LABEL_DESCRIPTION_GLOSSARY,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBannerUrlGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_BANNER_URL_GLOSSARY, TextType::class, [
            'label' => static::LABEL_BANNER_URL_GLOSSARY,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDeliveryTimeGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DELIVERY_TIME_GLOSSARY, TextType::class, [
            'label' => static::LABEL_DELIVERY_TIME_GLOSSARY,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTermsConditionsGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TERMS_CONDITIONS_GLOSSARY, TextareaType::class, [
            'label' => static::LABEL_TERMS_CONDITIONS_GLOSSARY,
            'required' => false,
            'attr' => [
                'class' => 'html-editor',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCancellationPolicyGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CANCELLATION_POLICY_GLOSSARY, TextareaType::class, [
            'label' => static::LABEL_CANCELLATION_POLICY_GLOSSARY,
            'required' => false,
            'attr' => [
                'class' => 'html-editor',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImprintGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMPRINT_GLOSSARY, TextareaType::class, [
            'label' => static::LABEL_IMPRINT_GLOSSARY,
            'required' => false,
            'attr' => [
                'class' => 'html-editor',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDataPrivacyGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DATA_PRIVACY_GLOSSARY, TextareaType::class, [
            'label' => static::LABEL_DATA_PRIVACY_GLOSSARY,
            'required' => false,
            'attr' => [
                'class' => 'html-editor',
            ],
        ]);

        return $this;
    }
}
