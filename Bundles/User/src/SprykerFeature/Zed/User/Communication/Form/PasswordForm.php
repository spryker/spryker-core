<?php

namespace SprykerFeature\Zed\User\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;

class PasswordForm extends AbstractForm
{
    /**
     * @return array
     */
    protected function getDefaultData()
    {
        return [];
    }

    public function addFormFields()
    {
        $this->addField('password');
    }

}
