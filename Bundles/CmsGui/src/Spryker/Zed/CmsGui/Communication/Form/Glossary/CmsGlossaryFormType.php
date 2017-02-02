<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Glossary;

use Spryker\Zed\CmsGui\Communication\Form\ArrayObjectTransformerTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsGlossaryFormType extends AbstractType
{

    const FIELD_GLOSSARY_ATTRIBUTES = 'glossaryAttributes';

    const OPTION_DATA_CLASS_ATTRIBUTES = 'data_class_glossary_attributes';

    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    protected $cmsGlossaryAttributeFormType;

    use ArrayObjectTransformerTrait;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $cmsGlossaryAttributeFormType
     */
    public function __construct(FormTypeInterface $cmsGlossaryAttributeFormType)
    {
        $this->cmsGlossaryAttributeFormType = $cmsGlossaryAttributeFormType;
    }

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
            'type' => $this->cmsGlossaryAttributeFormType,
            'allow_add' => true,
            'entry_options' => [
                'data_class' => $options[static::OPTION_DATA_CLASS_ATTRIBUTES],
            ],
        ]);

        $builder->get(self::FIELD_GLOSSARY_ATTRIBUTES)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

}
