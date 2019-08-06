<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-05
 * Time: 09:35
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
class StoreSubForm extends AbstractType
{
    protected const FIELD_ID_STORE = 'idStore';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addIdStore($builder);
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /**
             * @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer
             */
            $storeTransfer = $event->getData();
            $event->getForm()->getParent()->get(PriceProductScheduleForm::FIELD_CURRENCY)->add(CurrencySubForm::FIELD_ID_CURRENCY, ChoiceType::class, [
                'label' => 'Currency',
                'placeholder' => 'Choose currency',
                'choices' => array_flip($this->getFactory()->createPriceProductScheduleFormDataProvider()->getCurrencyValues($storeTransfer->getIdStore())),
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdStore(FormBuilderInterface $builder)
    {
        $storeValues = array_flip($this->getFactory()->createPriceProductScheduleFormDataProvider()->getStoreValues());
        $builder->add(static::FIELD_ID_STORE, ChoiceType::class, [
            'label' => 'Store',
            'choices' => $storeValues,
            'placeholder' => 'Choose store',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
