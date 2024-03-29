<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Communication\Form;

use Generated\Shared\Transfer\DummyPaymentTransfer;
use Spryker\Shared\DummyPayment\DummyPaymentConfig;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\DummyPayment\DummyPaymentConfig getConfig()
 * @method \Spryker\Zed\DummyPayment\Business\DummyPaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\DummyPayment\Communication\DummyPaymentCommunicationFactory getFactory()
 */
class InvoiceSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const PAYMENT_METHOD = 'invoice';

    /**
     * @var string
     */
    public const FIELD_DATE_OF_BIRTH = 'date_of_birth';

    /**
     * @var string
     */
    public const MIN_BIRTHDAY_DATE_STRING = '-18 years';

    /**
     * @var string
     */
    public const OPTIONS_FIELD_NAME = 'select_options';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addDateOfBirth($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DummyPaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDateOfBirth(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_DATE_OF_BIRTH,
            BirthdayType::class,
            [
                'label' => 'Birth date dd.MM.yyyy',
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'input' => 'string',
                'attr' => [
                    'placeholder' => 'Birth date',
                ],
                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createBirthdayConstraint(),
                ],
            ],
        );

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint(): Constraint
    {
        return new NotBlank(['groups' => $this->getPropertyPath()]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createBirthdayConstraint(): Constraint
    {
        return new Callback([
            'callback' => function ($date, ExecutionContextInterface $context) {
                if (strtotime($date) > strtotime(static::MIN_BIRTHDAY_DATE_STRING)) {
                    $context->addViolation('Must be older than 18 years');
                }
            },
            'groups' => $this->getPropertyPath(),
        ]);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return DummyPaymentConfig::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @return string
     */
    protected function getPropertyPath(): string
    {
        return DummyPaymentConfig::PAYMENT_METHOD_INVOICE;
    }
}
