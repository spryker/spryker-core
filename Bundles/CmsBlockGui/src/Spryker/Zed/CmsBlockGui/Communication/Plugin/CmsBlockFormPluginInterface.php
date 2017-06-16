<?php

namespace Spryker\Zed\CmsBlockGui\Communication\Plugin;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

interface CmsBlockFormPluginInterface
{

    /**
     * @param FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder);

}