<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockProductConnector\Communication\CmsBlockProductConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorRepositoryInterface getRepository()
 */
class CmsBlockProductAbstractType extends AbstractType
{
    public const FIELD_ID_CMS_BLOCK = 'id_cms_block';
    public const FIELD_ID_PRODUCT_ABSTRACTS = 'id_product_abstracts';
    public const PLACEHOLDER_ID_PRODUCT_ABSTRACTS = 'Type three letters of name or sku for suggestions.';

    /**
     * @deprecated Use \Spryker\Zed\CmsBlockProductConnector\Communication\Form\CmsBlockProductAbstractType::OPTION_ASSIGNED_PRODUCT_ABSTRACTS instead.
     */
    public const OPTION_PRODUCT_ABSTRACT_ARRAY = 'option-assigned-product-abstracts';

    public const OPTION_ASSIGNED_PRODUCT_ABSTRACTS = 'option-assigned-product-abstracts';
    public const OPTION_PRODUCT_AUTOCOMPLETE_URL = 'option-autocomplete-url';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addProductsAbstractField($builder, $options);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, $this->getProductSearchPreSubmitCallback());
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addProductsAbstractField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ID_PRODUCT_ABSTRACTS, Select2ComboBoxType::class, [
            'label' => 'Products',
            'multiple' => true,
            'required' => false,
            'choices' => $options[static::OPTION_ASSIGNED_PRODUCT_ABSTRACTS],
            'attr' => [
                'placeholder' => static::PLACEHOLDER_ID_PRODUCT_ABSTRACTS,
                'data-autocomplete-url' => $options[static::OPTION_PRODUCT_AUTOCOMPLETE_URL],
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'products';
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
     * @return \Closure
     */
    protected function getProductSearchPreSubmitCallback(): callable
    {
        return function (FormEvent $formEvent) {
            $data = $formEvent->getData();
            $form = $formEvent->getForm();

            if (empty($data[static::FIELD_ID_PRODUCT_ABSTRACTS])) {
                return;
            }
            if ($form->has(static::FIELD_ID_PRODUCT_ABSTRACTS)) {
                $form->remove(static::FIELD_ID_PRODUCT_ABSTRACTS);
            }
            $form->add(
                static::FIELD_ID_PRODUCT_ABSTRACTS,
                ChoiceType::class,
                [
                    'label' => 'Products',
                    'attr' => [
                        'placeholder' => static::PLACEHOLDER_ID_PRODUCT_ABSTRACTS,
                    ],
                    'required' => false,
                    'choices' => $data[static::FIELD_ID_PRODUCT_ABSTRACTS],
                    'multiple' => true,
                ]
            );
        };
    }
}
