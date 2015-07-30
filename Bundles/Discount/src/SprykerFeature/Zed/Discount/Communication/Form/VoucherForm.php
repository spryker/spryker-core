<?php

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class VoucherForm extends AbstractForm
{
    /**
     * Prepares form
     *
     * @return VoucherForm
     */
    protected function buildFormFields()
    {
        $this
            ->addChoice('poll', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getPolls(),
            ])
            ->addText('name')
            ->addChoice('validity', [
                'label' => 'Salutation',
                'placeholder' => 'Select one',
                'choices' => $this->getValidity(),
            ])
            ->addCheckbox('combine', [
                'label' => 'Combinable',
            ])
        ;
    }

    /**
     * @return array
     */
    private function getValidity()
    {
        $vouchers = [];

        for ($i=3; $i<=20; $i++) {
            $vouchers[$i] = $i . ' Years';
        }

        return $vouchers;
    }

    /**
     * Set the values for fields
     *
     * @return $this
     */
    protected function populateFormFields()
    {
        return [];
    }

}
