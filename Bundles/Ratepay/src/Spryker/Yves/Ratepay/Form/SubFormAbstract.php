<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Form;

use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

abstract class SubFormAbstract extends AbstractSubFormType implements SubFormInterface
{
    public const FIELD_DATE_OF_BIRTH = 'date_of_birth';
    public const FIELD_PHONE = 'phone';
    public const FIELD_ALLOW_CREDIT_INQUIRY = 'customer_allow_credit_inquiry';

    public const MIN_BIRTHDAY_DATE_STRING = '-18 years';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addDateOfBirth($builder)
            ->addPhone($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDateOfBirth(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_DATE_OF_BIRTH,
            'birthday',
            [
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'input' => 'string',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createBirthdayConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPhone(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_PHONE,
            'text',
            [
                'label' => false,
                'required' => true,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param string|null $groups
     *
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint($groups = null)
    {
        $groups = ($groups === null) ? $this->getPropertyPath() : $groups;
        return new NotBlank(['groups' => $groups]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createBirthdayConstraint()
    {
        return new Callback([
            'methods' => [
                function ($date, ExecutionContextInterface $context) {
                    if (strtotime($date) > strtotime(self::MIN_BIRTHDAY_DATE_STRING)) {
                        $context->addViolation('checkout.step.payment.must_be_older_than_18_years');
                    }
                },
            ],
            'groups' => $this->getPropertyPath(),
        ]);
    }
}
