<?php

namespace SprykerFeature\Zed\Gui\Communication;

use Symfony\Component\Form\AbstractType;

abstract class AbstractFormType extends AbstractType
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'form';
    }
}
