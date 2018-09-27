<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\General;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class GeneralFormType extends AbstractType
{
    public const FIELD_LOCALIZED_GENERAL_FORM_COLLECTION = 'localized_general_form_collection';
    public const FIELD_IS_ACTIVE = 'is_active';
    public const FIELD_ID_PRODUCT_SET = 'id_product_set';
    public const FIELD_PRODUCT_SET_KEY = 'product_set_key';
    public const FIELD_PRODUCT_SET_KEY_ORIGINAL = 'product_set_key_original';
    public const FIELD_WEIGHT = 'weight';

    public const GROUP_UNIQUE_KEY_CHECK = 'unique_key_check';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $originalKey = $form->get(static::FIELD_PRODUCT_SET_KEY_ORIGINAL)->getData();
                $updatedKey = $form->get(static::FIELD_PRODUCT_SET_KEY)->getData();

                if ($originalKey !== $updatedKey) {
                    return [Constraint::DEFAULT_GROUP, static::GROUP_UNIQUE_KEY_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
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
        $this->addProductSetDataFieldCollection($builder)
            ->addProductSetKeyField($builder)
            ->addProductSetKeyOriginalField($builder)
            ->addWeightField($builder)
            ->addIsActiveField($builder)
            ->addIdProductSetField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductSetDataFieldCollection(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALIZED_GENERAL_FORM_COLLECTION, CollectionType::class, [
            'entry_type' => LocalizedGeneralFormType::class,
            'constraints' => [
                new Callback([
                    'callback' => [$this, 'validateLocalizedUrls'],
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductSetKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_SET_KEY, TextType::class, [
            'label' => 'Product Set Key *',
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'callback' => [$this, 'validateUniqueKey'],
                    'groups' => [static::GROUP_UNIQUE_KEY_CHECK],
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductSetKeyOriginalField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_SET_KEY_ORIGINAL, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addWeightField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_WEIGHT, NumberType::class, [
            'label' => 'Weight',
            'attr' => [
                'placeholder' => 'Defines sorting order. Product Sets with higher numbers listed first.',
            ],
        ]);

        $builder->get(static::FIELD_WEIGHT)
            ->addModelTransformer(new CallbackTransformer(
                function ($weight) {
                    return $weight;
                },
                function ($weight) {
                    return (int)$weight;
                }
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_ACTIVE, CheckboxType::class, [
            'label' => 'Active',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductSetField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_SET, HiddenType::class);

        return $this;
    }

    /**
     * @param array $localizedGeneralForms
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function validateLocalizedUrls(array $localizedGeneralForms, ExecutionContextInterface $context)
    {
        $uniqueUrls = [];
        foreach ($localizedGeneralForms as $localizedGeneralForm) {
            $url = $localizedGeneralForm[LocalizedGeneralFormType::FIELD_URL];
            if (in_array($url, $uniqueUrls)) {
                $context->addViolation('URLs must be different for each locale.');
                break;
            }
            $uniqueUrls[] = $url;
        }
    }

    /**
     * @param string $productSetKey
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function validateUniqueKey($productSetKey, ExecutionContextInterface $context)
    {
        if (!$productSetKey) {
            return;
        }

        $count = $this->getQueryContainer()
            ->queryProductSetByKey($productSetKey)
            ->count();

        if ($count) {
            $context->addViolation('Product Set Key already exists.');
        }
    }
}
