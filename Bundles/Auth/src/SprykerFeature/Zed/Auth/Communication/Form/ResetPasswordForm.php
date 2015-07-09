<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordForm extends AbstractForm
{

    /**
     * @return array
     */
    public function addFormFields()
    {
        $fields[] = $this->addField('username')
            ->setConstraints([
                new Assert\Required([
                    new Assert\Type([
                        'type' => 'string',
                    ]),
                ]),
            ]);

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
