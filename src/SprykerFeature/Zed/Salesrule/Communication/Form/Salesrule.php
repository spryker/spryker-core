<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form;

use SprykerFeature\Zed\Library\Form;
use Symfony\Component\HttpFoundation\Request;

class Salesrule extends Form
{

    public function init()
    {
        $this->setAction('/salesrule/salesrule-form/save');
        $this->setAttrib('id', 'sales-rule-form');

        $hidden = new \Zend_Form_Element_Hidden('id_salesrule');
        $this->addElement($hidden);

        $name = new \Zend_Form_Element_Text('name');
        $name->setLabel(__('Name'))
            ->setRequired();
        $this->addElement($name);

        $displayName = new \Zend_Form_Element_Text('display_name');
        $displayName->setLabel(__('Display Name'))
            ->setRequired();
        $this->addElement($displayName);

        $actionArray = $this->facadeSalesrule->getSalesruleActions();
        $action = new \Zend_Form_Element_Select('action');
        $action->setLabel(__('Action'))
            ->addMultiOptions($actionArray);
        $this->addElement($action);

        $amount = new \Zend_Form_Element_Text('amount');
        $amount->setLabel(__('Amount'))
            ->setRequired()
            ->setDescription(__('Amount of "20" means either 20% off or reduction by $20 depending on the chosen action.'));
        $this->addElement($amount);

        $scopeArray = array('global' => 'Global', 'local' => 'Local');
        $scope = new \Zend_Form_Element_Select('scope');
        $scope->setLabel(__('Scope'))
            ->addMultiOptions($scopeArray);
        $this->addElement($scope);

        $isActive = new \Zend_Form_Element_Checkbox('is_active');
        $isActive->setLabel(__('Is Active'))
            ->setDescription(__('Should the discount be active?'));

        if (!Request::createFromGlobals()->query->get('id-sales-rule')) {
            $isActive->setAttrib('disabled', true);
        }

        $this->addElement($isActive);

        $description = new \Zend_Form_Element_Textarea('description');
        $description->setLabel(__('Description'));
        $description->setAttrib('rows', 5);
        $this->addElement($description);

        $saveButton = new \Zend_Form_Element_Submit('Save');
        $this->addElement($saveButton);
    }
}
