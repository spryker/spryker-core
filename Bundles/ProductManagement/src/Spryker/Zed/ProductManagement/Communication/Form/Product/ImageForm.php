<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Spryker\Zed\ProductManagement\Communication\Form\AbstractSubForm;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ImageForm extends AbstractSubForm
{

    const FIELD_SET_ID = 'id_product_image_set';
    const FIELD_SET_NAME = 'name';
    const FIELD_SET_FK_LOCALE = 'fk_locale';
    const FIELD_SET_FK_PRODUCT = 'fk_product';
    const FIELD_SET_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    const PRODUCT_IMAGES = 'product_images';


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
            ->addImageForm($builder);
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
                'label' => 'Image Set Name',
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
    protected function addImageForm(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::PRODUCT_IMAGES, 'collection', [
                'type' => new ImageCollectionForm(ProductFormAdd::FORM_IMAGE_COLLECTION),
                'label' => false,
                //'allow_add' => true,
                //'allow_delete' => true,
                //'prototype' => true,
/*                'constraints' => [new Callback([
                    'methods' => [
                        function ($attributes, ExecutionContextInterface $context) {
                            return;
                            $selectedAttributes = [];
                            foreach ($attributes as $type => $valueSet) {
                                if (!empty($valueSet['value'])) {
                                    $selectedAttributes[] = $valueSet['value'];
                                    break;
                                }
                            }

                            if (empty($selectedAttributes)) {
                                $context->addViolation('Please select at least one variant attribute value');
                            }
                        },
                    ],
                    'groups' => [ProductFormAdd::VALIDATION_GROUP_IMAGE]
                ])]*/
            ]);

        return $this;
    }

}
