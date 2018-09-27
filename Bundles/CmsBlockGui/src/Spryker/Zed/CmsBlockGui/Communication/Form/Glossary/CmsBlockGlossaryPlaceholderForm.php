<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Form\Glossary;

use Spryker\Zed\CmsBlockGui\Communication\Form\ArrayObjectTransformerTrait;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class CmsBlockGlossaryPlaceholderForm extends AbstractType
{
    public const FIELD_FK_CMS_BLOCK = 'fkCmsBlock';
    public const FIELD_PLACEHOLDER = 'placeholder';
    public const FIELD_ID_GLOSSARY_KEY_MAPPING = 'idCmsBlockGlossaryKeyMapping';
    public const FIELD_TEMPLATE_NAME = 'templateName';
    public const FIELD_TRANSLATIONS = 'translations';
    public const FIELD_TRANSLATION_KEY = 'translationKey';

    public const GROUP_PLACEHOLDER_CHECK = 'placeholder_check';

    use ArrayObjectTransformerTrait;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = $form->getConfig()->getData();

                if (!isset($defaultData[static::FIELD_ID_GLOSSARY_KEY_MAPPING])) {
                    return [Constraint::DEFAULT_GROUP, static::GROUP_PLACEHOLDER_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addFkCmsBlockField($builder)
            ->addIdCmsBlockGlossaryKeyMappingField($builder)
            ->addTemplateNameField($builder)
            ->addPlaceholderField($builder)
            ->addTranslationsField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkCmsBlockField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_CMS_BLOCK, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCmsBlockGlossaryKeyMappingField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_GLOSSARY_KEY_MAPPING, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTemplateNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TEMPLATE_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPlaceholderField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PLACEHOLDER, TextType::class, [
            'label' => 'Placeholder',
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTranslationsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TRANSLATIONS, CollectionType::class, [
            'entry_type' => $this->getFactory()->getCmsBlockGlossaryPlaceholderTranslationFormType(),
            'allow_add' => true,
        ]);

        $builder->get(static::FIELD_TRANSLATIONS)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms_block_glossary_placeholder';
    }
}
