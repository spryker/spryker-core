<?php

namespace Spryker\Zed\CompanySupplierGui\Communication\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface ProductConcreteEditFormExpanderPluginInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options);
}