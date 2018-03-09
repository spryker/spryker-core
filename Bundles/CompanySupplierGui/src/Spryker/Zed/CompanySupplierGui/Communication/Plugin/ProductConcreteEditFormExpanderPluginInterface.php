<?php

namespace Spryker\Zed\CompanySupplier\Communication\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface ProductConcreteEditFormExpanderPluginInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options);
}