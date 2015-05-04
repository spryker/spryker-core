<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model\Condition;

use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Library\Form;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Business\Model\Condition;
use SprykerFeature\Zed\Salesrule\Business\Model\Salesrule;

abstract class AbstractCondition
{

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var string
     */
    public static $conditionName;

    /**
     * @var string
     */
    public static $conditionFacadeGetter;

    /**
     * @var array
     */
    protected $allowedConfigKeys;

    /**
     * @var bool
     */
    protected $testMode = false;

    /**
     * @param array|null $configuration
     */
    public function __construct(array $configuration = null)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return null|array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return string
     */
    public static function getConditionName()
    {
        if (null === static::$conditionName) {
            return get_called_class();
        }
        return static::$conditionName;
    }

    /**
     * @return array
     */
    public function getAllowedConfigKeys()
    {
        return $this->allowedConfigKeys;
    }

    /**
     * @return \Zend_Form
     */
    protected function getFormTemplate()
    {
        $request = Request::createFromGlobals();
        $form = new Form();
        $hidden = new \Zend_Form_Element_Hidden(Condition::CONDITION_NAME_ARRAY_KEY);
        $hidden->setValue(static::$conditionFacadeGetter);
        $form->addElement($hidden);

        $hidden = new \Zend_Form_Element_Hidden(Condition::FK_SALESRULE_KEY);
        $hidden->setValue($request->query->get(Condition::ID_SALES_RULE_PARAMETER));
        $form->addElement($hidden);

        $hidden = new \Zend_Form_Element_Hidden(Condition::ID_SALES_RULE_CONDITION);
        $hidden->setValue($request->query->get(Condition::ID_SALES_RULE_CONDITION_PARAMETER));
        $form->addElement($hidden);

        $hidden = new \Zend_Form_Element_Hidden('condition-form-name');
        $hidden->setValue(static::$conditionFacadeGetter);
        $form->addElement($hidden);

        $hidden = new \Zend_Form_Element_Hidden(Salesrule::ID_SALES_RULE_URL_PARAMETER);
        $hidden->setValue($request->query->get(Salesrule::ID_SALES_RULE_URL_PARAMETER));
        $form->addElement($hidden);

        $form->setAttrib('data-ajax-form', true);
        $form->setAction('/salesrule/condition-ajax/edit');

        $form->setName(static::$conditionFacadeGetter);

        return $form;
    }

    /**
     * @param Order $order
     * @return mixed
     */
    abstract public function match(Order $order);

    /**
     * @abstract
     * @return \Zend_Form
     */
    abstract public function getForm();

    /**
     * @param $testMode
     * @return $this
     */
    public function setIsTestMode($testMode)
    {
        $this->testMode = $testMode;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTestMode()
    {
        return $this->testMode;
    }
}
