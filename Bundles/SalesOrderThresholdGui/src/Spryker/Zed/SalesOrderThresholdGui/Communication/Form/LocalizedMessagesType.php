<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\AbstractGlobalThresholdType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig getConfig()
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
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
        $this->addMessageField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addMessageField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_MESSAGE, TextType::class, [
            'required' => false,
            'constraints' => [
                new Callback(function ($value, ExecutionContextInterface $context) {
                    /** @var \Symfony\Component\Form\Form $form */
                    $form = $context->getObject();
                    $parentThresholdGroupForm = $form->getParent()->getParent();
                    $data = $parentThresholdGroupForm->getData();

                    if (empty($data[AbstractGlobalThresholdType::FIELD_THRESHOLD])) {
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
