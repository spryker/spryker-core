<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Form;

use Generated\Zed\Ide\FactoryAutoCompletion\AclCommunication;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Acl\Persistence\AclQueryContainer;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class GroupForm extends AbstractForm
{

    const FIELD_ID_ACL_GROUP = 'id_acl_group';
    const FIELD_NAME = 'name';

    /**
     * @var FactoryInterface|AclCommunication
     */
    protected $factory;

    /**
     * @var AclQueryContainer
     */
    protected $aclQueryContainer;

    /** @var int */
    protected $idGroup;

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

        if ($this->getGroupId()) {
            $query = $this->getAclQueryContainer()->queryGroupById($this->getGroupId());

            $entity = $query->findOne();

            $response = $entity->toArray();
            $response[self::FIELD_ID_ACL_GROUP] = (int) $response[self::FIELD_ID_ACL_GROUP];
        }

        return $response;
    }

    /**
     *
     */
    public function addFormFields()
    {
        $this->addField(self::FIELD_ID_ACL_GROUP);

        $this->addField(self::FIELD_NAME)
            ->setConstraints(
                [
                    new Assert\Type([
                        'type' => 'string',
                    ]),

                    new Assert\NotBlank(),

                    $this->factory->createConstraintGroupExistsConstraint(
                        $this->getGroupName(),
                        $this->getGroupId(),
                        $this->getLocator()
                    ),
                ]
            );
    }

    protected function getGroupName()
    {
        return $this->stateContainer->getRequestValue(self::FIELD_NAME);
    }

    /**
     * @return int
     */
    protected function getGroupId()
    {
        return $this->idGroup;
    }

    public function setGroupId($idGroup)
    {
        $this->idGroup = $idGroup;
    }

    /**
     * @return AclQueryContainer
     */
    protected function getAclQueryContainer()
    {
        return $this->aclQueryContainer;
    }

}
