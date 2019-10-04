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
    public const FIELD_DESCRIPTION_GLOSSARY = 'descriptionGlossaryKey';
    public const FIELD_BANNER_URL_GLOSSARY = 'bannerUrlGlossaryKey';
    public const FIELD_DELIVERY_TIME_GLOSSARY = 'deliveryTimeGlossaryKey';
    public const FIELD_TERMS_CONDITIONS_GLOSSARY = 'termsConditionsGlossaryKey';
    public const FIELD_CANCELLATION_POLICY_GLOSSARY = 'cancellationPolicyGlossaryKey';
    public const FIELD_IMPRINT_GLOSSARY = 'imprintGlossaryKey';
    public const FIELD_DATA_PRIVACY_GLOSSARY = 'dataPrivacyGlossaryKey';

    public const LABEL_DESCRIPTION_GLOSSARY = 'Description';
    public const LABEL_BANNER_URL_GLOSSARY = 'Banner URL';
    public const LABEL_DELIVERY_TIME_GLOSSARY = 'Average Delivery Time';
    public const LABEL_TERMS_CONDITIONS_GLOSSARY = 'Terms and Conditions';
    public const LABEL_CANCELLATION_POLICY_GLOSSARY = 'Cancellation Policy';
    public const LABEL_IMPRINT_GLOSSARY = 'Imprint';
    public const LABEL_DATA_PRIVACY_GLOSSARY = 'Data Privacy';

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
