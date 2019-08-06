<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-01
 * Time: 14:58
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;


use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class PriceProductScheduleForm extends AbstractType
{
    public const FIELD_PRICE_PRODUCT = 'priceProduct';
    public const FIELD_STORE = 'store';
    public const FIELD_CURRENCY = 'currency';
    public const FIELD_SUBMIT = 'submit';
    public const FIELD_ACTIVE_FROM = 'activeFrom';
    public const FIELD_ACTIVE_TO = 'activeTo';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addStore($builder)
            ->addCurrency($builder)
            ->addPriceProduct($builder)
            ->addCurrency($builder)
            ->addActiveFrom($builder)
            ->addActiveTo($builder)
            ->addSubmitField($builder);
    }

    protected function addPriceProduct(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRICE_PRODUCT, PriceProductSubForm::class, [
            'data_class' => PriceProductTransfer::class,
            'label' => false,
        ]);

        return $this;
    }

    protected function addStore(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_STORE, StoreSubForm::class, [
            'data_class' => StoreTransfer::class,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubmitField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_SUBMIT, SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-info',
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCurrency(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CURRENCY, CurrencySubForm::class, [
            'data_class' => CurrencyTransfer::class,
            'label' => false
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addActiveFrom(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ACTIVE_FROM, DateTimeType::class, [
            'date_widget' => 'single_text',
            'date_format' => 'yyyy-mm-dd',
            'time_widget' => 'choice',
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
    protected function addActiveTo(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ACTIVE_TO, DateTimeType::class, [
            'date_widget' => 'single_text',
            'date_format' => 'yyyy-mm-dd',
            'time_widget' => 'choice',
            'constraints' => [
                new NotBlank(),
            ]
        ]);

        return $this;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'price_product_schedule';
    }
}
