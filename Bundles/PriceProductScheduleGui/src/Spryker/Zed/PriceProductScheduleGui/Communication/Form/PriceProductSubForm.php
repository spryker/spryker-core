<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-05
 * Time: 10:18
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class PriceProductSubForm extends AbstractType
{
    protected const FIELD_FK_PRICE_TYPE = 'fk_price_type';
    protected const FIELD_MONEY_VALUE = 'moneyValue';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addFkPriceType($builder)
            ->addMoneyValue($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkPriceType(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_PRICE_TYPE, ChoiceType::class, [
            'label' => 'Price type',
            'placeholder' => 'Choose price type',
            'choices' => array_flip($this->getFactory()->createPriceProductScheduleFormDataProvider()->getPriceTypeValues()),
            'constraints' => [
                new NotBlank(),
            ]
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMoneyValue(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_MONEY_VALUE, MoneyValueSubForm::class, [
            'label' => false,
            'data_class' => MoneyValueTransfer::class,
        ]);

        return $this;
    }
}
