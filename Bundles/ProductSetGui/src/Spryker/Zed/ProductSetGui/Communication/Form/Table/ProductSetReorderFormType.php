<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form\Table;

use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\ProductSetGuiConfig getConfig()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class ProductSetReorderFormType extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_PRODUCT_SET_WEIGHT = 'product_set_weight';

    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @var string
     */
    public const OPTION_ID = 'id';

    /**
     * @var string
     */
    public const OPTION_DATA_ID = 'data-id';

    /**
     * @var string
     */
    protected const ATTR_SIZE = 'size';

    /**
     * @var string
     */
    protected const ATTR_CLASS = 'class';

    /**
     * @var string
     */
    protected const CLASS_PRODUCT_SET_WEIGHT = 'product_set_weight';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addProductSetWeightField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_LOCALE => null,
            static::OPTION_ID => null,
            static::OPTION_DATA_ID => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addProductSetWeightField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PRODUCT_SET_WEIGHT, FormattedNumberType::class, [
            'label' => false,
            'locale' => $options[static::OPTION_LOCALE],
            'required' => false,
            'attr' => [
                static::OPTION_ID => $options[static::OPTION_ID],
                static::OPTION_DATA_ID => $options[static::OPTION_DATA_ID],
                static::ATTR_SIZE => 4,
                static::ATTR_CLASS => static::CLASS_PRODUCT_SET_WEIGHT,
            ],
        ]);

        return $this;
    }
}
