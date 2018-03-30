<?php

namespace Spryker\Zed\OfferGui\Communication\Form;


use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\OfferGui\Communication\Form\Product\ItemType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class OfferType extends AbstractType
{
    public const FIELD_ID_OFFER = 'idOffer';

    public const FIELD_ITEM_COLLECTION = 'items';
    public const FIELD_PRODUCT_COLLECTION = 'product-collection';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->addIdOfferField($builder);
        $builder = $this->addItemCollection($builder);
    }

    protected function addIdOfferField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_OFFER, HiddenType::class);

        return $builder;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return FormBuilderInterface
     */
    protected function addItemCollection(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ITEM_COLLECTION, CollectionType::class, [
            'entry_type' => ItemType::class,
            'property_path' => 'quote.items',
            'label' => 'Added Items',
            'required' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'label' => false,
                'data_class' => ItemTransfer::class,
            ],
        ]);

        return $builder;
    }

//    protected function addNewItemsFields(FormBuilderInterface $builder)
//    {
//        for ($i = 1; $i <= 3; $i++) {
//            $builder->add(ItemType::class)
//        }
//    }
}