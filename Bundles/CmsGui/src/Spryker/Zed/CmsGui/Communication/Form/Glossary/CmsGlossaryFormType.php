<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Glossary;

use Spryker\Zed\CmsGui\Communication\Form\ArrayObjectTransformerTrait;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsGui\CmsGuiConfig getConfig()
 */
class CmsGlossaryFormType extends AbstractType
{
    use ArrayObjectTransformerTrait;

    public const FIELD_GLOSSARY_ATTRIBUTES = 'glossaryAttributes';

    public const OPTION_DATA_CLASS_ATTRIBUTES = 'data_class_glossary_attributes';

    public const OPTION_GLOSSARY_KEY_SEARCH_OPTIONS = 'glossaryKeySearchOptions';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCmsGlossaryAttributeFormCollection($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_DATA_CLASS_ATTRIBUTES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCmsGlossaryAttributeFormCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_GLOSSARY_ATTRIBUTES, CollectionType::class, [
            'entry_type' => CmsGlossaryAttributesFormType::class,
            'allow_add' => true,
            'entry_options' => [
                'data_class' => $options[static::OPTION_DATA_CLASS_ATTRIBUTES],
            ],
        ]);

        $builder->get(static::FIELD_GLOSSARY_ATTRIBUTES)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'cms_glossary';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
