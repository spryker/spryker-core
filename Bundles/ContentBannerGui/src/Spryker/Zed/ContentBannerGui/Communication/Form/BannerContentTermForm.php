<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ContentBannerGui\Communication\ContentBannerGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig getConfig()
 */
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

        $resolver->setNormalizer('constraints', function (Options $options, $value) {
                return array_merge($value, [
                    $this->getFactory()->createContentBannerConstraint(),
                ]);
        });
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
        $builder->add(static::FIELD_CLICK_URL, TextType::class, [
            'label' => static::LABEL_CLICK_URL,
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
        ]);

        return $this;
    }
}
