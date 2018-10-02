<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductSearch\Communication\ProductSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface getQueryContainer()
 */
class AttributeTranslationForm extends AbstractType
{
    public const FIELD_KEY_TRANSLATION = 'key_translation';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'translation';
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

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addAttributeNameTranslationField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributeNameTranslationField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_KEY_TRANSLATION, TextType::class, [
            'label' => 'Filter name *',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
