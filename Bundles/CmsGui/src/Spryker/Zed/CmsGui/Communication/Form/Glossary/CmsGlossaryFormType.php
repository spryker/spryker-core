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
 */
class CmsGlossaryFormType extends AbstractType
{
    const FIELD_GLOSSARY_ATTRIBUTES = 'glossaryAttributes';

    const OPTION_DATA_CLASS_ATTRIBUTES = 'data_class_glossary_attributes';

    const OPTION_GLOSSARY_KEY_SEARCH_OPTIONS = 'glossaryKeySearchOptions';

    use ArrayObjectTransformerTrait;

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
     * @return string
     */
    public function getName()
    {
        return 'cms_glossary';
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
            'type' => $this->getFactory()->createCmsGlossaryAttributesFormType(),
            'allow_add' => true,
            'entry_options' => [
                'data_class' => $options[static::OPTION_DATA_CLASS_ATTRIBUTES],
            ],
        ]);

        $builder->get(static::FIELD_GLOSSARY_ATTRIBUTES)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }
}
