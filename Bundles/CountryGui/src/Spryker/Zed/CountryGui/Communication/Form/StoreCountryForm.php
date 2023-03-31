<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Form;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @method \Spryker\Zed\CountryGui\Communication\CountryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CountryGui\CountryGuiConfig getConfig()
 */
class StoreCountryForm extends AbstractType
{
    /**
     * @var string
     */
    protected const COUNTRIES_TO_BE_ASSIGNED = 'countryCodesToBeAssigned';

    /**
     * @var string
     */
    protected const COUNTRIES_TO_BE_DE_ASSIGNED = 'countryCodesToBeDeAssigned';

    /**
     * @phpstan-var non-empty-string
     *
     * @var string
     */
    protected const COUNTRY_CODES_SEPARATOR = ',';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCountriesToBeAssignedField($builder);
        $this->addCountriesToBeDeAssignedField($builder);

        $this->addPreSubmitEventListener($builder);
    }

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

        $countryCodesToBeAssigned = explode(static::COUNTRY_CODES_SEPARATOR, $eventData[static::COUNTRIES_TO_BE_ASSIGNED]);
        $countryCodesToBeDeAssigned = explode(static::COUNTRY_CODES_SEPARATOR, $eventData[static::COUNTRIES_TO_BE_DE_ASSIGNED]);

        $newCountryCodes = array_merge(
            array_diff($formEvent->getForm()->getData()[StoreTransfer::COUNTRIES], $countryCodesToBeDeAssigned),
            $countryCodesToBeAssigned,
        );

        $formData[StoreTransfer::COUNTRIES] = $newCountryCodes;

        $formEvent->getForm()->setData($formData);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return $this
     */
    protected function addCountriesToBeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::COUNTRIES_TO_BE_ASSIGNED, HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'id' => static::COUNTRIES_TO_BE_ASSIGNED,
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return $this
     */
    protected function addCountriesToBeDeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::COUNTRIES_TO_BE_DE_ASSIGNED, HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'id' => static::COUNTRIES_TO_BE_DE_ASSIGNED,
                ],
            ]);

        return $this;
    }
}
