<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-01
 * Time: 14:58
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;


use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class PriceProductScheduleForm extends AbstractType
{
    protected const FIELD_PRICE_TYPE = 'priceProduct';
    protected const FIELD_STORE = 'store';
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addPriceType($builder)
            ->addStore($builder);
    }

    protected function addPriceType(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRICE_TYPE, Select2ComboBoxType::class, [
            'label' => 'Price type',
            'choices' => array_flip($this->getFactory()->createPriceProductScheduleFormDataProvider()->getPriceTypeValues()),
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    protected function addStore(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_STORE, Select2ComboBoxType::class, [
            'label' => 'Store',
            'choices' => array_flip($this->getFactory()->createPriceProductScheduleFormDataProvider()->getStoreValues()),
            'constraints' => [
                new NotBlank(),
            ],
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
