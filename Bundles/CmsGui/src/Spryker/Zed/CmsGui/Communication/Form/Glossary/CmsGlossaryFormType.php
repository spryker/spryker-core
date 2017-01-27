<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\Glossary;

use ArrayObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class CmsGlossaryFormType extends AbstractType
{
    const FIELD_GLOSSARY_ATTRIBUTES = 'glossaryAttributes';

    /**
     * @var \Spryker\Zed\CmsGui\Communication\Form\CmsGlossaryAttributesFormType
     */
    protected $cmsGlossaryAttributeFormType;

    /**
     * @param \Spryker\Zed\CmsGui\Communication\Form\CmsGlossaryAttributesFormType $cmsGlossaryAttributeFormType
     */
    public function __construct(CmsGlossaryAttributesFormType $cmsGlossaryAttributeFormType)
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
        $this->addCmsGlossaryAttributeFormCollection($builder);
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
     *
     * @return $this
     */
    protected function addCmsGlossaryAttributeFormCollection(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_GLOSSARY_ATTRIBUTES, CollectionType::class, [
            'type' => $this->cmsGlossaryAttributeFormType,
            'allow_add' => true,
        ]);

        $builder->get(self::FIELD_GLOSSARY_ATTRIBUTES)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createArrayObjectModelTransformer()
    {
        return new CallbackTransformer(
            function ($value) {
                return (array)$value;
            },
            function($value) {
                return new ArrayObject($value);
            }
        );
    }
}
