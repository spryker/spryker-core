<?php


namespace Spryker\Zed\Category\Dependency\Plugin;


use Symfony\Component\Form\FormBuilderInterface;

interface CategoryFormPluginInterface
{
    /**
     * Specification:
     * - Add form parts to the main form builder
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder);

}