<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class MoneyAmountForm extends AbstractType
{

    const FIELD_AMOUNT = 'amount';
    const FIELD_FK_CURRENCY = 'fk_currency';

    const MAX_MONEY_INT = 21474835;
    const MIN_MONEY_INT = 0;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addAmountField($builder)
            ->addFkCurrencyField($builder);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            $this->configureAmountField($formEvent->getForm(), $formEvent->getData());
        });

        $builder->addModelTransformer($this->getFactory()->createCurrencyAmountTransformer());
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer $discountAmountTransfer
     *
     * @return void
     */
    protected function configureAmountField(FormInterface $form, DiscountMoneyAmountTransfer $discountAmountTransfer)
    {
        /** @var \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfigurationTransfer */
        $discountConfigurationTransfer = $form->getRoot()->getData();

        $options = [];
        $calculatorPlugin = $this->getCalculatorPlugin($discountConfigurationTransfer->getDiscountCalculator()->getCalculatorPlugin());
        if ($calculatorPlugin) {
            $amountField = $form->get(static::FIELD_AMOUNT);
            $constraints = $amountField->getConfig()->getOption('constraints');
            $options['constraints'] = array_merge($constraints, $calculatorPlugin->getAmountValidators());
        }

        $form->remove(static::FIELD_AMOUNT);

        $options['currency'] = $discountAmountTransfer->getCurrencyCode();

        $this->addAmountField($form, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAmountField($builder, array $options = [])
    {
        $defaultOptions = [
            'attr' => [
                'class' => 'input-group',
            ],
            'constraints' => [
                new NotBlank([
                    'groups' => DiscountConstants::CALCULATOR_MONEY_INPUT_TYPE,
                ]),
                new LessThanOrEqual([
                    'value' => static::MAX_MONEY_INT,
                    'groups' => DiscountConstants::CALCULATOR_MONEY_INPUT_TYPE,
                ]),
                new GreaterThanOrEqual([
                    'value' => static::MIN_MONEY_INT,
                    'groups' => DiscountConstants::CALCULATOR_MONEY_INPUT_TYPE,
                ]),
            ],
        ];

        $builder->add(static::FIELD_AMOUNT,
            MoneyType::class,
            array_merge($defaultOptions, $options)
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkCurrencyField(FormBuilderInterface $builder)
    {
         $builder->add(static::FIELD_FK_CURRENCY, HiddenType::class);

         return $this;
    }

    /**
     * @param string $pluginName
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\CalculatorException
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    protected function getCalculatorPlugin($pluginName)
    {
        $calculatorPlugins = $this->getFactory()->getCalculatorPlugins();
        if (isset($calculatorPlugins[$pluginName])) {
            return $calculatorPlugins[$pluginName];
        }

        throw new CalculatorException(sprintf(
            'Calculator plugin with name "%s" not found.
            Have you added it to DiscountDependencyProvider::getAvailableCalculatorPlugins plugin stack?',
            $pluginName
        ));
    }

}
