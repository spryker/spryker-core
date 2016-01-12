<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Spryker\Zed\User\Dependency\Facade\UserToAclInterface;
use Symfony\Component\Form\FormBuilderInterface;

class UserForm extends AbstractForm
{

    const FIELD_USERNAME = 'username';
    const FIELD_GROUP = 'group';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_PASSWORD = 'password';
    const FIELD_STATUS = 'status';

    /**
     * @var array
     */
    protected $allAclGroups;

    /**
     * @var UserToAclInterface
     */
    protected $aclFacade;

    /**
     * UserForm constructor.
     *
     * @param UserToAclInterface $aclFacade
     */
    public function __construct(UserToAclInterface $aclFacade)
    {
        $this->aclFacade = $aclFacade;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_USERNAME, 'text', [
            'label' => 'Username',
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
        ])
        ->add(self::FIELD_PASSWORD, 'repeated', [
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
            'invalid_message' => 'The password fields must match.',
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
            'required' => true,
            'type' => 'password',
        ])
        ->add(self::FIELD_FIRST_NAME, 'text', [
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
        ])
        ->add(self::FIELD_LAST_NAME, 'text', [
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
        ])
        ->add(self::FIELD_GROUP, 'choice', [
            'constraints' => [
                $this->getConstraints()->createConstraintChoice([
                    'choices' => array_keys($this->getGroupChoices()),
                    'multiple' => true,
                    'min' => 1,
                ]),
            ],
            'label' => 'Assigned groups',
            'multiple' => true,
            'expanded' => true,
            'choices' => $this->getGroupChoices(),
        ]);
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }

    /**
     * @return array
     */
    protected function getGroupChoices()
    {
        if ($this->allAclGroups === null) {
            $groupsTransfer = $this->aclFacade->getAllGroups();

            foreach ($groupsTransfer->getGroups() as $groupTransfer) {
                $this->allAclGroups[$groupTransfer->getIdAclGroup()] =
                    $this->formatGroupName($groupTransfer->getName());
            }
        }

        return $this->allAclGroups;
    }

    /**
     * @param string $groupName
     *
     * @return string
     */
    protected function formatGroupName($groupName)
    {
        return str_replace('_', ' ', ucfirst($groupName));
    }

    /**
     * @return array
     */
    protected function getStatusSelectChoices()
    {
        return array_combine(
            SpyUserTableMap::getValueSet(SpyUserTableMap::COL_STATUS),
            SpyUserTableMap::getValueSet(SpyUserTableMap::COL_STATUS)
        );
    }

    /**
     * Set the values for fields
     *
     * @return array
     */
    public function populateFormFields()
    {
        return [];
    }

}
