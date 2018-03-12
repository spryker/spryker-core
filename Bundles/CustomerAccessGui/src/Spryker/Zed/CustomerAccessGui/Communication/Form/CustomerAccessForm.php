<?php

namespace Spryker\Zed\CustomerAccessGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use ArrayObject;

/**
 * @method \Spryker\Zed\CustomerAccessGui\Communication\CustomerAccessGuiCommunicationFactory getFactory
 */
class CustomerAccessForm extends AbstractType
{
    const FIELD_CONTENT_TYPE_ACCESS = 'contentTypeAccess';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addContentTypeAccess($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     */
    protected function addContentTypeAccess(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CONTENT_TYPE_ACCESS, ChoiceType::class, [
            'expanded' => true,
            'multiple' => true,
            'required' => false,
            'label' => 'Content Types',
            'choice_label' => 'contentType',
            'choice_value' => 'contentType',
            'choices' => $this->getFactory()->createCustomerAccessDataProvider()->getOptions()[static::FIELD_CONTENT_TYPE_ACCESS],
        ]);

        $builder
            ->get(self::FIELD_CONTENT_TYPE_ACCESS)
            ->addModelTransformer(new CallbackTransformer(function($customerAccess) {
                if ($customerAccess) {
                    return (array)$customerAccess;
                }

                return [];
            }, function($customerAccess) {
                return new ArrayObject($customerAccess);
            }));

        return $this;
    }
}
