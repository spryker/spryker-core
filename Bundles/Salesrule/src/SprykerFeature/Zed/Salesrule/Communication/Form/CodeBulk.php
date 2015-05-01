<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form;

use SprykerFeature\Zed\Library\Form;
use SprykerFeature\Zed\Salesrule\Business\Model\Codepool as OtherCodePool;

class CodeBulk extends Form
{

    /**
     * @var int
     */
    protected $idCodePool;

    /**
     * @param int $idCodePool
     */
    public function __construct($idCodePool)
    {
        $this->idCodePool = $idCodePool;
        parent::__construct();
    }

    public function init()
    {
        $amount = new \Zend_Form_Element_Text('amount');
        $amount->setLabel(__('Amount'))
            ->setRequired(true)
            ->addValidator(new \Zend_Validate_Int())
            ->addValidator(new \Zend_Validate_GreaterThan(0))
            ->setDescription('Please enter the amount of new codes to be generated');

        $this->addElement($amount);

        $hidden = new \Zend_Form_Element_Hidden(OtherCodePool::ID_CODE_POOL);
        $hidden->setValue($this->idCodePool);
        $this->addElement($hidden);

        $saveButton = new \Zend_Form_Element_Submit('save');
        $saveButton->setLabel(__('Generate Codes'));
        $this->addElement($saveButton);

        $this->setAction('/salesrule/code-ajax/index?' . OtherCodePool::ID_CODE_POOL_URL_PARAMETER . '=' . $this->idCodePool);
        $this->setAttrib('data-code-bulk-form', true);
    }
}
