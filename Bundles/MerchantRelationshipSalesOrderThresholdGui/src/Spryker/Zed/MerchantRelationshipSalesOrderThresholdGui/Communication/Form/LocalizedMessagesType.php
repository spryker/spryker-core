<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\AbstractMerchantRelationshipThresholdType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Persistence\MerchantRelationshipSalesOrderThresholdGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory getFactory()
 */
class LocalizedMessagesType extends AbstractType
{
    public const FIELD_MESSAGE = 'message';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addMessageField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addMessageField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::FIELD_MESSAGE, TextType::class, [
                'required' => false,
                'constraints' => [
                    new Callback(function ($value, ExecutionContextInterface $context) {
                        /** @var \Symfony\Component\Form\Form $form */
                        $form = $context->getObject();
                        $parentThresholdGroupForm = $form->getParent()->getParent();
                        $data = $parentThresholdGroupForm->getData();

                        if (empty($data[AbstractMerchantRelationshipThresholdType::FIELD_THRESHOLD])) {
                            return;
                        }

                        if (empty($value)) {
                            $context->buildViolation((new NotBlank())->message)->addViolation();
                        }
                    }),
                ],
            ]);

        return $this;
    }
}
