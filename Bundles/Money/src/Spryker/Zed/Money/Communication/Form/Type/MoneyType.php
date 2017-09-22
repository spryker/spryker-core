<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\Type;

use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Spryker\Zed\Discount\Business\Exception\CalculatorException;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\Money\Communication\MoneyCommunicationFactory getFactory()
 */
class MoneyType extends AbstractType
{
    const FIELD_NET_AMOUNT = 'net_amount';
    const FIELD_GROSS_AMOUNT = 'gross_amount';
    const FIELD_FK_CURRENCY = 'fk_currency';
    const FIELD_FK_STORE = 'fk_store';

    const MAX_MONEY_INT = 21474835;
    const MIN_MONEY_INT = 0;

    const OPTION_VALIDATION_GROUPS = 'validation_groups';

    const REGULAR_EXPRESSION_MONEY_VALUE = '/[0-9\.\,]+/';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addFieldAmount($builder, static::FIELD_NET_AMOUNT, $options)
            ->addFieldAmount($builder, static::FIELD_GROSS_AMOUNT, $options)
            ->addFieldFkCurrency($builder)
            ->addFieldFkStore($builder);

        $builder->addModelTransformer($this->getFactory()->createCurrencyAmountTransformer());
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_VALIDATION_GROUPS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     * @param string $fieldName
     * @param array $options
     * @return $this
     */
    protected function addFieldAmount($builder, $fieldName, array $options = [])
    {
        $defaultOptions = [
            'attr' => [
                'class' => 'input-group',
            ],
            'constraints' => [
                new LessThanOrEqual([
                    'value' => static::MAX_MONEY_INT,
                    'groups' => $options[static::OPTION_VALIDATION_GROUPS],
                ]),
                new GreaterThanOrEqual([
                    'value' => static::MIN_MONEY_INT,
                    'groups' => $options[static::OPTION_VALIDATION_GROUPS],
                ]),
                new Regex([
                    'pattern' => static::REGULAR_EXPRESSION_MONEY_VALUE,
                    'groups' => $options[static::OPTION_VALIDATION_GROUPS],
                ]),
            ],
        ];

        $builder->add($fieldName, SimpleMoneyType::class, $defaultOptions);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldFkCurrency(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_CURRENCY, HiddenType::class);

        return $this;
    }
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldFkStore(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_STORE, HiddenType::class);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $viewData = $form->getViewData();
        if (!method_exists($viewData, 'getCurrency')) {
            throw new \Exception(sprintf(
                'Transfer object "%s" missing "%s" method which should provide currency transfer for current formType.',
                get_class($viewData),
                'getCurrency'
            ));
        }

        $view->vars['currency_symbol'] = $viewData->getCurrency()->getSymbol();
        $view->vars['store_name'] = $viewData->getCurrency()->getStore()->getName();
    }
}
