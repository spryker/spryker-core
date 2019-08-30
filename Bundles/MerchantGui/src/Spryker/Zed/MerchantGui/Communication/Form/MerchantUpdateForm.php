<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class MerchantUpdateForm extends MerchantForm
{
    public const OPTION_CURRENT_ID = 'current_id';
    public const OPTION_STATUS_CHOICES = 'status_choices';
    protected const FIELD_STATUS = 'status';

    protected const LABEL_STATUS = 'Status';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_STATUS_CHOICES);
        $resolver->setRequired(static::OPTION_CURRENT_ID);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdMerchantField($builder)
            ->addNameField($builder)
            ->addEmailField($builder, $options[static::OPTION_CURRENT_ID])
            ->addRegistrationNumberField($builder)
            ->addContactPersonTitleField($builder, $options[static::SALUTATION_CHOICES_OPTION])
            ->addContactPersonFirstNameField($builder)
            ->addContactPersonLastNameField($builder)
            ->addContactPersonPhoneField($builder)
            ->addStatusField($builder, $options[static::OPTION_STATUS_CHOICES])
            ->addAddressCollectionSubform($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addStatusField(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add(static::FIELD_STATUS, ChoiceType::class, [
            'label' => static::LABEL_STATUS,
            'constraints' => $this->getStatusFieldConstraints($choices),
            'choices' => array_combine(
                array_values(array_map([$this, 'getStatusLabel'], $choices)),
                array_values($choices)
            ),
            'choices_as_values' => true,
            'placeholder' => false,
        ]);

        return $this;
    }

    /**
     * @param string $statusKey
     *
     * @return string
     */
    protected function getStatusLabel(string $statusKey): string
    {
        return sprintf('%s', $statusKey);
    }

    /**
     * @param array $choices
     *
     * @return array
     */
    protected function getStatusFieldConstraints(array $choices = []): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 64]),
            new Choice(['choices' => array_values($choices)]),
        ];
    }

    /**
     * @param int|null $currentId
     *
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getEmailFieldConstraints(?int $currentId = null): array
    {
        $merchantFacade = $this->getFactory()->getMerchantFacade();

        return [
            new Required(),
            new NotBlank(),
            new Email(),
            new Length(['max' => 255]),
            new Callback([
                'callback' => $this->getExistingEmailValidationCallback($currentId, $merchantFacade),
            ]),
        ];
    }

    /**
     * @param int|null $currentId
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface $merchantFacade
     *
     * @return callable
     */
    protected function getExistingEmailValidationCallback(?int $currentId, MerchantGuiToMerchantFacadeInterface $merchantFacade): callable
    {
        return function ($email, ExecutionContextInterface $context) use ($merchantFacade, $currentId) {
            $mrchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
            $mrchantCriteriaFilterTransfer->setEmail($email);
            $merchantTransfer = $merchantFacade->findOne($mrchantCriteriaFilterTransfer);
            if ($merchantTransfer !== null && $merchantTransfer->getIdMerchant() !== $currentId) {
                $context->addViolation('Email is already used.');
            }
        };
    }
}
