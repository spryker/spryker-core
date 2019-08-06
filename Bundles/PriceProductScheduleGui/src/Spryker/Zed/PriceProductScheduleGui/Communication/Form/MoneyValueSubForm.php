<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-05
 * Time: 14:12
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;


use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class MoneyValueSubForm extends AbstractType
{
    protected const FIELD_NET_AMOUNT = 'netAmount';
    protected const FIELD_GROSS_AMOUNT = 'grossAmount';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNetPrice($builder)
            ->addGrossPrice($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNetPrice(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NET_AMOUNT, IntegerType::class, [
            'label' => 'Net price',
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
    protected function addGrossPrice(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_GROSS_AMOUNT, IntegerType::class, [
            'label' => 'Gross price',
            'constraints' => [
                new NotBlank(),
            ]
        ]);

        return $this;
    }
}
