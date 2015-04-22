<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model\Condition;

use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Zed\Library\Form\Element\DateTimePicker;
use SprykerFeature\Zed\Library\Validator\DateTimeFormat;
use SprykerFeature\Zed\Salesrule\Business\Model\Logger;
use SprykerFeature\Zed\Salesrule\Communication\Form\Validator\EndDateAfterStartDate;

final class DateBetween extends AbstractCondition
{
    const CONFIG_KEY_START_DATE = 'start_date';
    const CONFIG_KEY_END_DATE = 'end_date';

    /** @var string */
    public static $conditionName = 'Date between';

    /** @var string */
    public static $conditionFacadeGetter = 'ConditionDateBetween';

    /** @var array */
    protected $allowedConfigKeys = [
        self::CONFIG_KEY_START_DATE,
        self::CONFIG_KEY_END_DATE,
    ];

    /**
     * @param Order $order
     * @return bool|mixed
     */
    public function match(Order $order)
    {
        assert(null !== $this->configuration);
        $configuration = $this->configuration;
        $now = time();

        if (strtotime($configuration[self::CONFIG_KEY_START_DATE]) <= $now && strtotime($configuration[self::CONFIG_KEY_END_DATE]) >= $now) {
            Logger::getInstance()->log(
                static::$conditionName . ': ' . strtotime($configuration[self::CONFIG_KEY_START_DATE])
                . ' <= ' . $now . ' && ' . strtotime($configuration[self::CONFIG_KEY_END_DATE]) . ' >= ' . $now
            );
            return true;
        }

        return false;
    }

    /**
     * @return \Zend_Form
     */
    public function getForm()
    {
        $startDateTimePicker = new \Zend_Form_Element_Text(self::CONFIG_KEY_START_DATE);
        $startDateTimePicker->setAttribs(['style' => 'width:300px'])
            ->setRequired()
            ->addValidator(new DateTimeFormat())
            ->setLabel('Start Date');

        $endDateTimePicker = new \Zend_Form_Element_Text(self::CONFIG_KEY_END_DATE);
        $endDateTimePicker->setAttribs(['style' => 'width:300px'])
            ->setRequired()
            ->addValidator(new DateTimeFormat())
            ->addValidator(new EndDateAfterStartDate(self::CONFIG_KEY_START_DATE))
            ->setLabel('End Date');

        $saveButton = new \Zend_Form_Element_Submit('Save');

        $form = $this->getFormTemplate()
            ->addElements([
                    $startDateTimePicker,
                    $endDateTimePicker,
                    $saveButton,
                ]);

        return $form;
    }

}
