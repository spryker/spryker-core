<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class CurrencySubForm extends AbstractType
{
    public const FIELD_ID_CURRENCY = 'idCurrency';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdCurrency($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCurrency(FormBuilderInterface $builder)
    {
        $idStore = $this->findIdStoreFromFormData($builder->getData());
        $currencyChoices = $this->getCurrencyChoices($idStore);

        $builder->add(static::FIELD_ID_CURRENCY, ChoiceType::class, [
            'label' => 'Currency',
            'disabled' => true,
            'placeholder' => 'Choose currency',
            'choices' => $currencyChoices,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param int|null $idStore
     *
     * @return array
     */
    protected function getCurrencyChoices(?int $idStore): array
    {
        if ($idStore === null) {
            return [];
        }

        return array_flip(
            $this->getFactory()
                ->createPriceProductScheduleFormDataProvider()
                ->getOptions($idStore)[PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $formData
     *
     * @return int|null
     */
    protected function findIdStoreFromFormData(?StoreTransfer $formData): ?int
    {
        if ($formData === null) {
            return null;
        }

        return $formData->getIdStore();
    }
}
