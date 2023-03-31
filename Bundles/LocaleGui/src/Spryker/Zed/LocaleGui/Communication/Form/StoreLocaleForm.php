<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Form;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\LocaleGui\Communication\LocaleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\LocaleGui\LocaleGuiConfig getConfig()
 */
class StoreLocaleForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_LOCALE_CHOICES = 'locale_choices';

    /**
     * @var string
     */
    protected const DEFAULT_LOCALE = 'defaultLocaleIsoCode';

    /**
     * @var string
     */
    protected const LOCALES_TO_BE_ASSIGNED = 'localeCodesToBeAssigned';

    /**
     * @var string
     */
    protected const LOCALES_TO_BE_DE_ASSIGNED = 'localeCodesToBeDeAssigned';

    /**
     * @phpstan-var non-empty-string
     *
     * @var string
     */
    protected const LOCALE_NAMES_SEPARATOR = ',';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return void
     */
    protected function addPreSubmitEventListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent): void {
            $this->executePreSubmitHandler($formEvent);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    protected function executePreSubmitHandler(FormEvent $formEvent): void
    {
        $eventData = $formEvent->getData();
        $formData = $formEvent->getForm()->getData();

        $localeCodesToBeAssigned = explode(static::LOCALE_NAMES_SEPARATOR, $eventData[static::LOCALES_TO_BE_ASSIGNED]);
        $localeCodesToBeDeAssigned = explode(static::LOCALE_NAMES_SEPARATOR, $eventData[static::LOCALES_TO_BE_DE_ASSIGNED]);

        $newLocaleCodes = array_merge(
            array_diff($formEvent->getForm()->getData()[StoreTransfer::AVAILABLE_LOCALE_ISO_CODES], $localeCodesToBeDeAssigned),
            $localeCodesToBeAssigned,
        );

        $formData[StoreTransfer::AVAILABLE_LOCALE_ISO_CODES] = $newLocaleCodes;

        $formEvent->getForm()->setData($formData);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return $this
     */
    protected function addLocalesToBeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::LOCALES_TO_BE_ASSIGNED, HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'id' => static::LOCALES_TO_BE_ASSIGNED,
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return $this
     */
    protected function addLocalesToBeDeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::LOCALES_TO_BE_DE_ASSIGNED, HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'id' => static::LOCALES_TO_BE_DE_ASSIGNED,
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_LOCALE_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addDefaultLocaleFied($builder, $options[static::OPTION_LOCALE_CHOICES]);
        $this->addLocalesToBeAssignedField($builder);
        $this->addLocalesToBeDeAssignedField($builder);

        $this->addPreSubmitEventListener($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param array<string> $choices
     *
     * @return $this
     */
    protected function addDefaultLocaleFied(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::DEFAULT_LOCALE, Select2ComboBoxType::class, [
            'multiple' => false,
            'choices' => $choices,
            'constraints' => $this->getLocalesFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getLocalesFieldConstraints(): array
    {
        return [
            new NotBlank(),
        ];
    }
}
