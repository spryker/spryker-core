<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Form;

use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductOptionTranslationForm extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_LOCALE_CODE = 'localeCode';
    const FIELD_KEY = 'key';
    const FIELD_RELATED_OPTION_HASH = 'relatedOptionHash';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNameField($builder)
            ->addLocalCode($builder)
            ->addKey($builder)
            ->addRelatedProductOptionValueHash($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductOptionTranslationTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME, 'text', [
            'label' => 'Value *',
            'required' => false,
            'constraints' => [
                new NotBlank(),
             ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalCode(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_LOCALE_CODE, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addKey(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_KEY, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRelatedProductOptionValueHash(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_RELATED_OPTION_HASH, 'hidden');

        return $this;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'product_option_value_translation';
    }

}
