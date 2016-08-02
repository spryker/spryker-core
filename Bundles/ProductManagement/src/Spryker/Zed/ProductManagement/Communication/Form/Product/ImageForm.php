<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageForm extends AbstractSubForm
{

    const FIELD_SET_ID = 'id_product_image_set';
    const FIELD_SET_NAME = 'name';
    const FIELD_SET_FK_LOCALE = 'fk_locale';
    const FIELD_SET_FK_PRODUCT = 'fk_product';
    const FIELD_SET_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    const FIELD_ID_PRODUCT_IMAGE = 'id_product_image';
    const FIELD_IMAGE_SMALL = 'external_url_small';
    const FIELD_IMAGE_BIG = 'external_url_large';
    const FIELD_ORDER = 'order';


    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this
            ->addSetIdField($builder)
            ->addNameField($builder)
            ->addLocaleHiddenField($builder)
            ->addProductHiddenField($builder)
            ->addProductAbstractHiddenField($builder)
            ->addProductImageIdHiddenField($builder)
            ->addImageSmallField($builder)
            ->addImageBigField($builder)
            ->addOrderHiddenField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSetIdField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SET_ID, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SET_NAME, 'text', [
                'required' => false,
                'label' => 'Image Set Name'
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocaleHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SET_FK_LOCALE, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SET_FK_PRODUCT, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductAbstractHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SET_FK_PRODUCT_ABSTRACT, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductImageIdHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ID_PRODUCT_IMAGE, 'hidden', []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImageSmallField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_IMAGE_SMALL, 'text', [
                'required' => true,
                'label' => 'Small',
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
    protected function addImageBigField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_IMAGE_BIG, 'text', [
                'required' => true,
                'label' => 'Large',
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
    protected function addOrderHiddenField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_ORDER, 'hidden', []);

        return $this;
    }

}
