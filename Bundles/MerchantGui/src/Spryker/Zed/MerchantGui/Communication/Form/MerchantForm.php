<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class MerchantForm extends AbstractType
{
    protected const FIELD_ID_MERCHANT = 'id_merchant';
    protected const FIELD_MERCHANT_KEY = 'merchant_key';
    protected const FIELD_NAME = 'name';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'merchant';
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
            ->addMerchantKeyField($builder)
            ->addNameField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\MerchantGui\Communication\Form\MerchantForm
     */
    protected function addIdMerchantField(FormBuilderInterface $builder): MerchantForm
    {
        $builder->add(static::FIELD_ID_MERCHANT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\MerchantGui\Communication\Form\MerchantForm
     */
    protected function addMerchantKeyField(FormBuilderInterface $builder): MerchantForm
    {
        $builder->add(static::FIELD_MERCHANT_KEY, TextType::class, [
            'label' => 'Merchant key',
            'constraints' => $this->getMerchantKeyFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Spryker\Zed\MerchantGui\Communication\Form\MerchantForm
     */
    protected function addNameField(FormBuilderInterface $builder): MerchantForm
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'Name',
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getMerchantKeyFieldConstraints(): array
    {
        $constraints = $this->getTextFieldConstraints();

        $constraints[] = new Callback([
            'callback' => $this->getUniqueMerchantKeyConstraint(),
        ]);

        return $constraints;
    }

    /**
     * @return callable
     */
    protected function getUniqueMerchantKeyConstraint(): callable
    {
        return function (string $merchantKey, ExecutionContextInterface $context) {
            $form = $context->getRoot();
            $idMerchant = $form->get(MerchantForm::FIELD_ID_MERCHANT)->getData();

            $keyCount = $this->getFactory()->getPropelMerchantQuery()
                ->filterByIdMerchant($idMerchant, Criteria::NOT_EQUAL)
                ->filterByMerchantKey($merchantKey)
                ->count();

            if ($keyCount > 0) {
                $context->addViolation(
                    sprintf('The merchant key "%s" is already used', $merchantKey)
                );
            }
        };
    }
}
