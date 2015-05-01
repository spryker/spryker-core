<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form;

use SprykerFeature\Zed\Library\Form;
use SprykerFeature\Zed\Salesrule\Business\Model\Codepool as OtherCodePool;
use SprykerFeature\Zed\Salesrule\Communication\Form\Validator\Code as CodeValidator;

class Code
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
    }

    public function init()
    {
        $code = new \Zend_Form_Element_Text('code');
        $code->setLabel(__('Code'))
            ->setRequired(true)
            ->addValidator(new \Zend_Validate_NotEmpty())
            ->addValidator(new CodeValidator())
            ->addValidator(new \Zend_Validate_StringLength(2, 255))
            ->setDescription('Please enter a new unique code');

        $this->addElement($code);

        $saveButton = new \Zend_Form_Element_Submit('save');
        $saveButton->setLabel(__('Generate Code'));
        $this->addElement($saveButton);

        $hidden = new \Zend_Form_Element_Hidden(OtherCodePool::ID_CODE_POOL);
        $hidden->setValue($this->idCodePool);
        $this->addElement($hidden);

        $this->setAction('/salesrule/code-ajax/index?' . OtherCodePool::ID_CODE_POOL_URL_PARAMETER . '=' . $this->idCodePool);
        $this->setAttrib('data-code-form', true);
    }
}
