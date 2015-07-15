<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UiExample\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\Validator\Constraints as Assert;
use SprykerFeature\Zed\Ui\Library\Constraints as SerializeAssert;

class CarForm extends AbstractForm
{

    /**
     * @return array|\SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field[]
     */
    public function addFormFields()
    {
        $this->addField('whees')
            ->setLabel('Wheels')
            ->setConstraints([
                new SerializeAssert\Type([
                    'type' => 'integer',
                ]),
                new Assert\NotBlank(),
            ]);

        $this->addField('shock_absorber')
            ->setLabel('Shock absorber')
            ->setConstraints([
                new SerializeAssert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $data = [
            'wheels' => 'Standard',
            'shock_absorber' => 'Fox',
        ];

        return $data;
    }

}
