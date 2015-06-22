<?php

namespace SprykerFeature\Zed\User\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;

class DetailsUserForm extends AbstractForm
{
    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $userId = $this->stateContainer->getRequestValue('id');

        $userDetails = $this->queryContainer
            ->queryUserById(
                $userId
            )
            ->findOne()
        ;

        if (is_null($userDetails)) {
            return [];
        }

        return [
            'first_name' => $userDetails->getFirstName(),
            'last_name' => $userDetails->getLastName(),
            'username' => $userDetails->getUsername(),
            'status' => (0 == $userDetails->getStatus()),
        ];
    }

    public function addFormFields()
    {
        $this
            ->addField('first_name')
            ->setLabel('First Name')
        ;
        $this
            ->addField('last_name')
            ->setLabel('Last Name')
        ;
        $this
            ->addField('username')
            ->setLabel('Username')
        ;
        $this
            ->addField('status')
            ->setLabel('Status')
        ;
    }
}
