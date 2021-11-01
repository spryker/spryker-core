<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\MerchantProfileGlossary;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint\HtmlTagWhitelistConstraint;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 */
class MerchantProfileGlossaryAttributeValuesFormType extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_DESCRIPTION_GLOSSARY = 'descriptionGlossaryKey';

    /**
     * @var string
     */
    protected const FIELD_BANNER_URL_GLOSSARY = 'bannerUrlGlossaryKey';

    /**
     * @var string
     */
    protected const FIELD_DELIVERY_TIME_GLOSSARY = 'deliveryTimeGlossaryKey';

    /**
     * @var string
     */
    protected const FIELD_TERMS_CONDITIONS_GLOSSARY = 'termsConditionsGlossaryKey';

    /**
     * @var string
     */
    protected const FIELD_CANCELLATION_POLICY_GLOSSARY = 'cancellationPolicyGlossaryKey';

    /**
     * @var string
     */
    protected const FIELD_IMPRINT_GLOSSARY = 'imprintGlossaryKey';

    /**
     * @var string
     */
    protected const FIELD_DATA_PRIVACY_GLOSSARY = 'dataPrivacyGlossaryKey';

    /**
     * @var string
     */
    protected const LABEL_DESCRIPTION_GLOSSARY = 'Description';

    /**
     * @var string
     */
    protected const LABEL_BANNER_URL_GLOSSARY = 'Banner URL';

    /**
     * @var string
     */
    protected const LABEL_DELIVERY_TIME_GLOSSARY = 'Average Delivery Time';

    /**
     * @var string
     */
    protected const LABEL_TERMS_CONDITIONS_GLOSSARY = 'Terms and Conditions';

    /**
     * @var string
     */
    protected const LABEL_CANCELLATION_POLICY_GLOSSARY = 'Cancellation Policy';

    /**
     * @var string
     */
    protected const LABEL_IMPRINT_GLOSSARY = 'Imprint';

    /**
     * @var string
     */
    protected const LABEL_DATA_PRIVACY_GLOSSARY = 'Data Privacy';

    /**
     * @var string
     */
    protected const PLACEHOLDER_DESCRIPTION_GLOSSARY = 'Tell us about your store';

    /**
     * @var string
     */
    protected const PLACEHOLDER_BANNER_URL_GLOSSARY = 'Provide a banner URL';

    /**
     * @var string
     */
    protected const PLACEHOLDER_DELIVERY_TIME_GLOSSARY = 'Provide Average Delivery Time';

    /**
     * @var string
     */
    protected const PLACEHOLDER_TERMS_CONDITIONS_GLOSSARY = 'Provide Terms & Conditions';

    /**
     * @var string
     */
    protected const PLACEHOLDER_CANCELLATION_POLICY_GLOSSARY = 'Provide Cancellation Policy';

    /**
     * @var string
     */
    protected const PLACEHOLDER_IMPRINT_GLOSSARY = 'Provide Imprint';

    /**
     * @var string
     */
    protected const PLACEHOLDER_DATA_PRIVACY_GLOSSARY = 'Provide Data Privacy';

    /**
     * @see \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Constraint\HtmlTagWhitelistConstraint::$allowedHtmlTags
     *
     * @var string
     */
    protected const CONSTRAINT_OPTION_ALLOWED_HTML_TAGS = 'allowedHtmlTags';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
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
        $builder->add(static::FIELD_DESCRIPTION_GLOSSARY, TextareaType::class, [
            'label' => static::LABEL_DESCRIPTION_GLOSSARY,
            'attr' => [
                'placeholder' => static::PLACEHOLDER_DESCRIPTION_GLOSSARY,
            ],
            'required' => false,
            'constraints' => $this->getTextareaConstrains(),
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
            'attr' => [
                'placeholder' => static::PLACEHOLDER_BANNER_URL_GLOSSARY,
            ],
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
            'attr' => [
                'placeholder' => static::PLACEHOLDER_DELIVERY_TIME_GLOSSARY,
            ],
            'required' => false,
            'constraints' => $this->getTextareaConstrains(),
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
                'placeholder' => static::PLACEHOLDER_TERMS_CONDITIONS_GLOSSARY,
            ],
            'constraints' => $this->getTextareaConstrains(),
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
                'placeholder' => static::PLACEHOLDER_CANCELLATION_POLICY_GLOSSARY,
            ],
            'constraints' => $this->getTextareaConstrains(),
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
                'placeholder' => static::PLACEHOLDER_IMPRINT_GLOSSARY,
            ],
            'constraints' => $this->getTextareaConstrains(),
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
                'placeholder' => static::PLACEHOLDER_DATA_PRIVACY_GLOSSARY,
            ],
            'constraints' => $this->getTextareaConstrains(),
        ]);

        return $this;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getTextareaConstrains(): array
    {
        return [
            new HtmlTagWhitelistConstraint([
                static::CONSTRAINT_OPTION_ALLOWED_HTML_TAGS => $this->getConfig()->getHtmlTagWhitelist(),
            ]),
        ];
    }
}
