<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UiExample\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use SprykerFeature\Zed\Ui\Library\Constraints as SerializeAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CopterForm extends AbstractForm
{

    /**
     * @return array|\SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field[]
     */
    public function addFormFields()
    {
        $this->addField('motor_amount')
            ->setLabel('Amount of motors')
            ->setConstraints([
                new SerializeAssert\Type([
                    'type' => 'integer',
                ]),
                new Assert\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return (int) $value;
            });
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $data = [
            'motor_amount' => 5,
        ];

        return $data;
    }

}
