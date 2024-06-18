<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Form;

use Generated\Shared\Transfer\StoreApplicationContextTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @method \Spryker\Zed\StoreContextGui\Communication\StoreContextGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\StoreContextGui\StoreContextGuiConfig getConfig()
 */
class StoreContextForm extends AbstractType
{
    /**
     * @var string
     */
    protected const LABEL_DEFAULT_APPLICATION = 'Default';

    /**
     * @var string
     */
    protected const LABEL_TIMEZONE = 'Timezone';

    /**
     * @var string
     */
    protected const LABEL_APPLICATION = 'Application';

    /**
     * @var string
     */
    protected const OPTION_APPLICATIONS = 'applications';

    /**
     * @var string
     */
    protected const OPTION_TIMEZONES = 'timezones';

    /**
     * @var string
     */
    protected const FIELD_ID = 'id';

    /**
     * @var string
     */
    protected const FIELD_APPLICATION = 'application';

    /**
     * @var string
     */
    protected const FIELD_TIMEZONE = 'timezone';

    /**
     * @var string
     */
    protected const OPTION_REQUIRED = 'required';

    /**
     * @var string
     */
    protected const OPTION_LABEL = 'label';

    /**
     * @var string
     */
    protected const OPTION_CHOICES = 'choices';

    /**
     * @var string
     */
    protected const OPTION_MULTIPLE = 'multiple';

    /**
     * @var string
     */
    protected const OPTION_PLACEHOLDER = 'placeholder';

    /**
     * @var string
     */
    protected const OPTION_DATA_CLASS = 'data_class';

    /**
     * @var string
     */
    protected const OPTION_CONSTRAINTS = 'constraints';

    /**
     * @var string
     */
    protected const OPTION_COMPOUND = 'compound';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_TIMEZONES,
            static::OPTION_APPLICATIONS,
        ]);

        $resolver->setDefaults([
            static::OPTION_DATA_CLASS => StoreApplicationContextTransfer::class,
            static::OPTION_CONSTRAINTS => new Valid(),
            static::OPTION_REQUIRED => false,
            static::OPTION_COMPOUND => true,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'store_context_set';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this
            ->addApplicationField($builder, $options)
            ->addTimezoneField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addApplicationField(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(static::FIELD_APPLICATION, SelectType::class, [
                static::OPTION_REQUIRED => false,
                static::OPTION_LABEL => static::LABEL_APPLICATION,
                static::OPTION_CHOICES => $this->getApplicationsChoices($options),
                static::OPTION_MULTIPLE => false,
                static::OPTION_PLACEHOLDER => static::LABEL_DEFAULT_APPLICATION,
                'attr' => [
                    'class' => 'select-application',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addTimezoneField(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(static::FIELD_TIMEZONE, SelectType::class, [
                static::OPTION_REQUIRED => true,
                static::OPTION_LABEL => static::LABEL_TIMEZONE,
                static::OPTION_MULTIPLE => false,
                static::OPTION_CHOICES => $options[static::OPTION_TIMEZONES],
            ]);

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, string>
     */
    protected function getApplicationsChoices(array $options): array
    {
        return array_combine($options[static::OPTION_APPLICATIONS], $options[static::OPTION_APPLICATIONS]);
    }
}
