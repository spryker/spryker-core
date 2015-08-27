<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Form;

use Generated\Zed\Ide\FactoryAutoCompletion\AclCommunication;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Acl\Messages\Messages;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UserForm extends AbstractForm
{

    const FIELD_USER_ID = 'id_user';
    const FIELD_USER_FIRST_NAME = 'first_name';
    const FIELD_USER_LAST_NAME = 'last_name';
    const FIELD_USER_USERNAME = 'username';
    const FIELD_GROUP_ID = 'id_acl_group';
    const FIELD_GROUP_NAME = 'group_name';
    const FIELD_GROUP_LIST = 'groups';
    const FIELD_USER_PASSWORD = 'password';

    protected function buildFormFields()
    {
        // @todo: Implement buildFormFields() method.
    }

    protected function populateFormFields()
    {
        // @todo: Implement populateFormFields() method.
    }


    /**
     * @var FactoryInterface|AclCommunication
     */
    protected $factory;

    /**
     * @var AclQueryContainer
     */
    protected $aclQueryContainer;

    /** @var int */
    protected $idUser;

    /**
     * @param Request $request
     * @param LocatorLocatorInterface $locator
     * @param FactoryInterface $factory
     * @param AclQueryContainer $queryContainer
     */
    public function __construct(
        Request $request,
        FactoryInterface $factory,
        AclQueryContainer $queryContainer = null
    ) {
        $this->factory = $factory;
        $this->aclQueryContainer = $queryContainer;
        parent::__construct($request);
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $response = [];

        if ($this->getUserId()) {
            $query = $this->getAclQueryContainer()->queryUsersWithGroup();
            $query->filterByIdUser($this->getUserId());

            $entity = $query->findOne();

            $response = $entity->toArray();
            $response[self::FIELD_GROUP_ID] = (int) $response[self::FIELD_GROUP_ID];
        }

        $response[self::FIELD_USER_PASSWORD] = null;

        return $response;
    }

    /**
     *
     */
    public function addFormFields()
    {
        $this->addField(self::FIELD_USER_ID);

        $this->addField(self::FIELD_GROUP_ID)
            ->setAccepts($this->getGroups())
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),

                new Assert\NotBlank(),

                new Assert\Choice([
                    'choices' => array_column($this->getGroups(), 'value'),
                    'message' => Messages::CHOOSE_GROUP_ERROR,
                ]),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            });

        $this->addField(self::FIELD_USER_FIRST_NAME)
            ->setConstraints(
                [
                    new Assert\Type([
                        'type' => 'string',
                    ]),

                    new Assert\NotBlank(),
                ]
            );

        $this->addField(self::FIELD_USER_LAST_NAME)
            ->setConstraints(
                [
                    new Assert\Type([
                        'type' => 'string',
                    ]),

                    new Assert\NotBlank(),
                ]
            );

        $usernameConstraints = [
            new Assert\Type([
                'type' => 'string',
            ]),

            new Assert\NotBlank(),
        ];

        $idUser = $this->getUserId();
        if (true === empty($idUser)) {
            $usernameConstraints[] = $this->factory->createConstraintUsernameExistsConstraint(
                $this->getUsername(),
                $this->getUserId(),
                $this->getLocator()
            );
        }

        $this->addField(self::FIELD_USER_USERNAME)
            ->setConstraints(
                $usernameConstraints
            );

        $this->addField(self::FIELD_USER_PASSWORD)
            ->setConstraints(
                [
                    new Assert\Type([
                        'type' => 'string',
                    ]),
                ]
            );
    }

    /**
     * @return array
     */
    protected function getGroups()
    {
        $response = [];

        $groups = $this->getLocator()->acl()->facade()->getAllGroups();

        foreach ($groups as $group) {
            $response[] = $this->formatOption(
                (int) $group->getIdAclGroup(),
                $group->getName()
            );
        }

        if (empty($response)) {
            $response[] = $this->formatOption('', '');
        }

        return $response;
    }

    /**
     * @param string $option
     * @param string $label
     *
     * @return array
     */
    protected function formatOption($option, $label)
    {
        return [
            'value' => $option,
            'label' => $label,
        ];
    }

    /**
     * @return int
     */
    protected function getUserId()
    {
        return $this->idUser;
    }

    public function setUserId($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return int
     */
    protected function getGroupId()
    {
        return $this->stateContainer->getRequestValue(self::FIELD_GROUP_ID);
    }

    protected function getUsername()
    {
        return $this->stateContainer->getRequestValue(self::FIELD_USER_USERNAME);
    }

    /**
     * @return AclQueryContainer
     */
    protected function getAclQueryContainer()
    {
        return $this->aclQueryContainer;
    }

}
