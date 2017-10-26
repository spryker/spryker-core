<?php


namespace Spryker\Yves\Checkout\Form\Filter;


interface SubFormFilterInterface
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function getSubForms();
}