<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form;

use SprykerFeature\Zed\Library\Form;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Communication\Validator\CodePool as Validator;

class CodePool extends Form
{

    /**
     * @throws \ErrorException
     * @throws \Zend_Form_Exception
     */
    public function init()
    {
        $this->setAttrib('id', 'code-pool-condition-form');

        $hidden = new \Zend_Form_Element_Hidden('id_salesrule_codepool');
        $this->addElement($hidden);

        $name = new \Zend_Form_Element_Text('name');
        $name->setLabel(__('Name'))
            ->setRequired(true);
        $this->addElement($name);

        $prefix = new \Zend_Form_Element_Text('prefix');
        $prefix->setLabel(__('Prefix'))
            ->setRequired(false);
        $this->addElement($prefix);

        $checkbox = new \Zend_Form_Element_Checkbox('is_reusable');
        $checkbox->setLabel(__('Is Reusable'))
            ->setRequired(false);
        $this->addElement($checkbox);

        $checkbox = new \Zend_Form_Element_Checkbox('is_once_per_customer');
        $checkbox->setLabel(__('One per Customer'))
            ->setRequired(false);
        $this->addElement($checkbox);

        $checkbox = new \Zend_Form_Element_Checkbox('is_refundable');
        $checkbox->setLabel(__('Is Refundable'))
            ->setRequired(false);
        $this->addElement($checkbox);

        $checkbox = new \Zend_Form_Element_Checkbox('is_active');
        $checkbox->setLabel(__('Is Active'))
            ->setRequired(false);
        $checkbox->setAttrib('checked', 'checked');
        $this->addElement($checkbox);

        $saveButton = new \Zend_Form_Element_Submit('Save');
        $this->addElement($saveButton);

        $hidden = new \Zend_Form_Element_Hidden('add-condition');
        $hidden->setValue($this->request->query->get('add-condition', 0));
        $hidden->setIgnore(true);
        $this->addElement($hidden);

        $hidden = new \Zend_Form_Element_Hidden('id-sales-rule');
        $hidden->setValue($this->request->query->get('id-sales-rule'));
        $hidden->setIgnore(true);
        $this->addElement($hidden);

        $this->setAction('/salesrule/code-pool-form/save');
        $this->setAttrib('data-ajax-code-pool-form', true);

        $this->addValidatorChain(new Validator());
    }
}
