<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints as Assert;

class PriceTypeForm extends AbstractForm
{

    protected function buildFormFields()
    {
        // @todo: Implement buildFormFields() method.
    }

    protected function populateFormFields()
    {
        // @todo: Implement populateFormFields() method.
    }

    /**
     * @return array|\SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field[]
     */
    public function addFormFields()
    {
        $this->addField('name')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);
    }

    /**
     * @return array
     */
    public function getDefaultData()
    {
        return [];
    }

}
