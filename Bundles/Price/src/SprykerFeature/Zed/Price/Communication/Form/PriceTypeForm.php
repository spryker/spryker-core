<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\Validator\Constraints as Assert;

class PriceTypeForm extends AbstractForm
{

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
