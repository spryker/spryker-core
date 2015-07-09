<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\Validator\Constraints as Assert;

class LoginForm extends AbstractForm
{

    /**
     * @return array
     */
    public function addFormFields()
    {
        $fields[] = $this->addField('username')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);

        $fields[] = $this->addField('password')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);

//        $fields[] = $this->addField('url')
//            ->setConstraints([
//                new Assert\Type([
//                    'type' => 'string'
//                ]),
//                new Assert\NotBlank()
//            ]);

        return $fields;
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        return [];
    }

}
