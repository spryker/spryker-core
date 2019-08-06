<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-05
 * Time: 10:12
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;


use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class CurrencySubForm extends AbstractType
{
    public const FIELD_ID_CURRENCY = 'idCurrency';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addIdCurrency($builder);

    }

    protected function addIdCurrency(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CURRENCY, ChoiceType::class, [
            'label' => 'Currency',
            'placeholder' => 'Choose currency',
            'choices' => array_flip($this->getFactory()->createPriceProductScheduleFormDataProvider()->getCurrencyValues($builder->getData()->getIdStore ?? null)),
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
