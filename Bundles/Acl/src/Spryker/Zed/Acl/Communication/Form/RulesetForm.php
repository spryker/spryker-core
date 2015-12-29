<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl\Communication\Form;

use Orm\Zed\Acl\Persistence\Map\SpyAclRuleTableMap;
use Spryker\Shared\Gui\Form\AbstractForm;
use Symfony\Component\Form\FormBuilderInterface;

class RulesetForm extends AbstractForm
{

    const FIELD_BUNDLE = 'bundle';
    const FIELD_CONTROLLER = 'controller';
    const FIELD_ACTION = 'action';
    const FIELD_TYPE = 'type';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_BUNDLE, 'text', [
            'label' => 'Bundle',
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
        ])
        ->add(self::FIELD_CONTROLLER, 'text', [
            'label' => 'Controller',
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
        ])
        ->add(self::FIELD_ACTION, 'text', [
            'label' => 'Action',
            'constraints' => [
                $this->getConstraints()->createConstraintNotBlank(),
            ],
        ])
        ->add(self::FIELD_TYPE, 'choice', [
            'label' => 'Permission',
            'choices' => $this->getPermissionSelectChoices(),
            'placeholder' => false,
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
        return 'ruleset';
    }

    /**
     * @return array
     */
    protected function getPermissionSelectChoices()
    {
        return array_combine(
            SpyAclRuleTableMap::getValueSet(SpyAclRuleTableMap::COL_TYPE),
            SpyAclRuleTableMap::getValueSet(SpyAclRuleTableMap::COL_TYPE)
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
