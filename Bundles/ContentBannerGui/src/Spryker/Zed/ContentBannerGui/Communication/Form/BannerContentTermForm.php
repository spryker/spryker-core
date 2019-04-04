<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Url;

class BannerContentTermForm extends AbstractType
{
    public const FIELD_TITLE = 'title';
    public const FIELD_SUBTITLE = 'subtitle';
    public const FIELD_IMAGE_URL = 'imageUrl';
    public const FIELD_CLICK_URL = 'clickUrl';
    public const FIELD_ALT_TEXT = 'altText';

    public const LABEL_TITLE = 'Title';
    public const LABEL_SUBTITLE = 'Subtitle';
    public const LABEL_IMAGE_URL = 'Image URL';
    public const LABEL_CLICK_URL = 'Click URL';
    public const LABEL_ALT_TEXT = 'Alt-text';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                /** @var \Generated\Shared\Transfer\LocalizedContentTransfer $localizedContentTransfer */
                $localizedContentTransfer = $form->getParent()->getData();
                if ($localizedContentTransfer->getFkLocale() === null) {
                    return [Constraint::DEFAULT_GROUP];
                }
                /** @var \Generated\Shared\Transfer\ContentBannerTermTransfer $contentBanner */
                $contentBanner = $form->getNormData();

                foreach ($contentBanner->toArray() as $field) {
                    if ($field) {
                        return [Constraint::DEFAULT_GROUP];
                    }
                }

                return [];
            },
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'banner';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addTitleField($builder);
        $this->addSubtitleField($builder);
        $this->addImageUrlField($builder);
        $this->addClickUrlField($builder);
        $this->addAltTextField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTitleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TITLE, TextType::class, [
            'label' => static::LABEL_TITLE,
            'constraints' => array_merge(
                $this->getTextFieldConstraints(),
                [
                    new Length(['max' => 64]),
                ]
            ),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubtitleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SUBTITLE, TextType::class, [
            'label' => static::LABEL_SUBTITLE,
            'constraints' => array_merge(
                $this->getTextFieldConstraints(),
                [
                    new Length(['max' => 128]),
                ]
            ),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMAGE_URL, TextType::class, [
            'label' => static::LABEL_IMAGE_URL,
            'constraints' => array_merge(
                $this->getTextFieldConstraints(),
                [
                    new Length(['max' => 1028]),
                    new Url(),
                ]
            ),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addClickUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CLICK_URL, UrlType::class, [
            'label' => static::LABEL_CLICK_URL,
            'constraints' => array_merge(
                $this->getTextFieldConstraints(),
                [
                    new Length(['max' => 1028]),
                    new Url(),
                ]
            ),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAltTextField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ALT_TEXT, TextType::class, [
            'label' => static::LABEL_ALT_TEXT,
            'constraints' => array_merge(
                $this->getTextFieldConstraints(),
                [
                    new Length(['max' => 125]),
                ]
            ),
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
        ];
    }
}
