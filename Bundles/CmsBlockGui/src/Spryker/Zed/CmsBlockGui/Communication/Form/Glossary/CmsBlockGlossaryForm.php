<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Form\Glossary;

use Spryker\Zed\CmsBlockGui\Communication\Form\ArrayObjectTransformerTrait;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class CmsBlockGlossaryForm extends AbstractType
{
    public const FIELD_GLOSSARY_PLACEHOLDERS = 'glossaryPlaceholders';
    public const OPTION_DATA_CLASS_PLACEHOLDERS = 'data_class_glossary_placeholders';

    use ArrayObjectTransformerTrait;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCmsBlockGlossaryPlaceholderFormCollection($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_DATA_CLASS_PLACEHOLDERS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCmsBlockGlossaryPlaceholderFormCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_GLOSSARY_PLACEHOLDERS, CollectionType::class, [
            'entry_type' => $this->getFactory()->getCmsBlockGlossaryPlaceholderFormType(),
            'allow_add' => true,
            'entry_options' => [
                'data_class' => $options[static::OPTION_DATA_CLASS_PLACEHOLDERS],
            ],
        ]);

        $builder->get(static::FIELD_GLOSSARY_PLACEHOLDERS)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms_block_glossary';
    }
}
