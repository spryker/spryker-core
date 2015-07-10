<?php

namespace SprykerFeature\Zed\User\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use SprykerFeature\Zed\User\Persistence\Propel\Map\SpyUserUserTableMap;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            'password' => '',
            'first_name' => $userDetails->getFirstName(),
            'last_name' => $userDetails->getLastName(),
            'username' => $userDetails->getUsername(),
            'status' => (SpyUserUserTableMap::COL_STATUS_ACTIVE === $userDetails->getStatus()),
        ];
    }

    public function addFormFields()
    {
        $this
            ->addField('first_name')
            ->setLabel('First Name')
            ->setConstraints([
                new NotBlank(),
            ])
        ;
        $this
            ->addField('last_name')
            ->setLabel('Last Name')
        ;
        $this
            ->addField('username')
            ->setLabel('Username')
            ->setConstraints([
                new NotBlank(),
            ])
        ;
        $this
            ->addField('password')
            ->setLabel('Password')
        ;
        $this
            ->addField('status')
            ->setLabel('Status')
        ;
    }

}
