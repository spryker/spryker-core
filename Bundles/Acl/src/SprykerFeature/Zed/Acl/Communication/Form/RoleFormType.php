<?php

namespace SprykerFeature\Zed\Acl\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\AbstractFormType;
use Symfony\Component\Form\FormBuilderInterface;

class RoleFormType extends AbstractFormType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
            'label' => 'Role new Name'
        ]);
    }
}
