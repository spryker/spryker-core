<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductFormAttributeValues extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_VALUE = 'value';

    /**
     * @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $attributeMetadataCollection;

    /**
     * @var string
     */
    protected $validationGroup;

    /**
     * @var int
     */
    protected $idLocale;

    /**
     * @param array $attributes
     * @param array $attributeMetadataCollection
     * @param string $validationGroup
     * @param int $idLocale
     */
    public function __construct(array $attributes, array $attributeMetadataCollection, $validationGroup, $idLocale)
    {
        $this->attributes = $attributes;
        $this->attributeMetadataCollection = $attributeMetadataCollection;
        $this->validationGroup = $validationGroup;
        $this->idLocale = $idLocale;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'productAttributeValues';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'required' => false,
            'cascade_validation' => true,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addCheckboxNameField($builder, $options)
            ->addValueField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCheckboxNameField(FormBuilderInterface $builder, array $options)
    {
        $name = $this->getLocalizedFieldName($builder->getName());

        $builder
            ->add(self::FIELD_NAME, 'checkbox', [
                'label' => $name
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\ProductManagementAttributeLocalizedTransfer[] $options
     *
     * @return $this
     */
    protected function addValueField(FormBuilderInterface $builder, array $options)
    {
        $name = $this->getLocalizedFieldName($builder->getName());

        $choices = [];
        foreach ($this->attributes as $attributeTransfer) {
            //sd($attributeTransfer->toArray());
        }

        $builder->add(self::FIELD_VALUE, new Select2ComboBoxType(), [
            'choices' => $choices,
            'multiple' => true,
            'label' => false,
/*            'constraints' => [
                new AttributeFieldNotBlank([
                    'attributeFieldValue' => self::FIELD_VALUE,
                    'attributeCheckboxFieldName' => self::FIELD_NAME,
                ]),
            ],*/
        ]);

        return $this;
    }

    /**
     * @param string $keyToLocalize
     *
     * @return string
     */
    protected function getLocalizedFieldName($keyToLocalize)
    {
        if (!isset($this->attributeMetadataCollection[$keyToLocalize])) {
            return $keyToLocalize;
        }

        return $this->attributeMetadataCollection[$keyToLocalize];
    }

}
