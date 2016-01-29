<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;

class UserUpdateForm extends UserForm
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove(self::FIELD_PASSWORD);

        $builder->add(self::FIELD_STATUS, 'choice', [
            'choices' => $this->getStatusSelectChoices(),
        ]);
    }

}
