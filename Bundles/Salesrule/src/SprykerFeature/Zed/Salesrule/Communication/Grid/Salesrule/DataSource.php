<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Grid\Salesrule;

class DataSource
{

    const CONDITION_DATE_BETWEEN_CONDITION = 'ConditionDateBetween';
    const CONDITION_VOUCHER_CODE_IN_POOL = 'ConditionVoucherCodeInPool';
    const DATA_KEY_SALES_RULE_CONDITION = 'sales_rule_condition';
    const DATA_KEY_SALES_RULE_CONDITION_CONFIGURATION = 'sales_rule_condition_configuration';
    const DATA_KEY_SALES_RULE_EXPIRATION_STATUS = 'sales_rule_expiration_status';
    const DATA_KEY_SALES_RULE_IS_ACTIVE = 'is_active';
    const DATA_KEY_SALES_RULE_CODE_POOL_NAME = 'sales_rule_code_pool_name';
    const CONFIG_START_DATE = 'start_date';
    const CONFIG_END_DATE = 'end_date';
    const TYPE_RUNNING = 'running';
    const TYPE_EXPIRED = 'expired';
    const TYPE_NOT_STARTED = 'not_started';
    const EXPIRATION_STATUS_RUNNING = 'icon-play';
    const EXPIRATION_STATUS_EXPIRED = 'icon-backward';
    const EXPIRATION_STATUS_NOT_STARTED = 'icon-forward';
    const EXPIRATION_STATUS_UNKNOWN = 'icon-question';
    const IS_ACTIVE = 'icon-check';
    const IS_NOT_ACTIVE = 'icon-check-empty';

    /**
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleQuery
     */
    protected function getQuery()
    {
        $query =  \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleQuery::create()
            ->joinSalesruleCondition(null, \Propel\Runtime\ActiveQuery\Criteria::LEFT_JOIN)
            ->withColumn(\SprykerFeature\Zed\Salesrule\Persistence\Propel\Map\SpySalesruleConditionTableMap::COL_CONDITION, self::DATA_KEY_SALES_RULE_CONDITION)
            ->withColumn(\SprykerFeature\Zed\Salesrule\Persistence\Propel\Map\SpySalesruleConditionTableMap::COL_CONFIGURATION, self::DATA_KEY_SALES_RULE_CONDITION_CONFIGURATION)
            ->addJoinCondition('SalesruleCondition', 'SalesruleCondition.condition = ?', 'ConditionDateBetween');
        return $query;
    }

    /**
     * @return array|\PropelObjectCollection
     */
    public function getData()
    {
        $data =  parent::getData();
        foreach ($data as &$salesRule) {
            if (array_key_exists(self::DATA_KEY_SALES_RULE_CONDITION, $salesRule) && array_key_exists(self::DATA_KEY_SALES_RULE_CONDITION_CONFIGURATION, $salesRule)) {
                if ($salesRule[self::DATA_KEY_SALES_RULE_CONDITION] == self::CONDITION_DATE_BETWEEN_CONDITION) {
                    $config = json_decode($salesRule[self::DATA_KEY_SALES_RULE_CONDITION_CONFIGURATION]);
                    $startDate = new \DateTime($config->{self::CONFIG_START_DATE}, new \DateTimeZone(\SprykerFeature_Shared_Library_Context::getInstance()->timezone));
                    $endDate = new \DateTime($config->{self::CONFIG_END_DATE}, new \DateTimeZone(\SprykerFeature_Shared_Library_Context::getInstance()->timezone));

                    if ($this->isConditionStillRunning($startDate, $endDate)) {
                        $salesRule[self::DATA_KEY_SALES_RULE_CONDITION] = $this->renderTimeIntervalConditionString(self::TYPE_RUNNING, $startDate, $endDate);
                        $salesRule[self::DATA_KEY_SALES_RULE_EXPIRATION_STATUS] = self::EXPIRATION_STATUS_RUNNING;
                    } elseif ($this->isConditionEndDateInThePast($endDate)) {
                            $salesRule[self::DATA_KEY_SALES_RULE_CONDITION] = $this->renderTimeIntervalConditionString(self::TYPE_EXPIRED, $startDate, $endDate);
                        $salesRule[self::DATA_KEY_SALES_RULE_EXPIRATION_STATUS] = self::EXPIRATION_STATUS_EXPIRED;
                    } else {
                        $salesRule[self::DATA_KEY_SALES_RULE_CONDITION] = $this->renderTimeIntervalConditionString(self::TYPE_NOT_STARTED, $startDate, $endDate);
                        $salesRule[self::DATA_KEY_SALES_RULE_EXPIRATION_STATUS] = self::EXPIRATION_STATUS_NOT_STARTED;
                    }
                } else {
                    $salesRule[self::DATA_KEY_SALES_RULE_CONDITION] = '';
                    $salesRule[self::DATA_KEY_SALES_RULE_EXPIRATION_STATUS] = self::EXPIRATION_STATUS_UNKNOWN;
                }
            }
            $salesRule[self::DATA_KEY_SALES_RULE_IS_ACTIVE] = $salesRule[self::DATA_KEY_SALES_RULE_IS_ACTIVE] == 1 ? self::IS_ACTIVE : self::IS_NOT_ACTIVE;

            $salesRule[self::DATA_KEY_SALES_RULE_CODE_POOL_NAME] = implode(', ', $this->getCodePoolNamesBySalesRuleId($salesRule['id_salesrule']));
        }

        return $data;
    }

    /**
     * @param string $type
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return string
     */
    protected function renderTimeIntervalConditionString($type, \DateTime $startDate, \DateTime $endDate)
    {
        $now = new \DateTime('now', new \DateTimeZone(\SprykerFeature_Shared_Library_Context::getInstance()->timezone));
        $result = '';
        switch ($type) {
            case self::TYPE_RUNNING:
                return $this->renderSalesRuleIsStillRunning();
            case self::TYPE_NOT_STARTED:
                return $this->renderSalesRuleNotStartedYet($startDate->diff($now));
            case self::TYPE_EXPIRED:
                return $this->renderSalesRuleExpiredSince($endDate->diff($now));
        }
        return $result;
    }

    /**
     * @param \DateTime $startDate
     * @return bool
     */
    protected function isConditionStartDateInTheFuture(\DateTime $startDate)
    {
        $now = new \DateTime('now', new \DateTimeZone(\SprykerFeature_Shared_Library_Context::getInstance()->timezone));
        $result = $startDate->diff($now)->invert;
        return $result;
    }

    /**
     * @param \DateTime $endDate
     * @return bool
     */
    protected function isConditionEndDateInThePast(\DateTime $endDate)
    {
        $now = new \DateTime('now', new \DateTimeZone(\SprykerFeature_Shared_Library_Context::getInstance()->timezone));
        return !$endDate->diff($now)->invert;
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return bool
     */
    protected function isConditionStillRunning(\DateTime $startDate, \DateTime $endDate)
    {
        return !$this->isConditionStartDateInTheFuture($startDate) && !$this->isConditionEndDateInThePast($endDate);
    }

    /**
     * @return mixed
     */
    protected function renderSalesRuleIsStillRunning()
    {
        return __('Running');
    }

    /**
     * @param \DateInterval $diff
     * @return string
     */
    protected function renderSalesRuleNotStartedYet(\DateInterval $diff)
    {
        return __('Will start in') . ' ' . $this->renderTimeString($diff);
    }

    /**
     * @param \DateInterval $diff
     * @return string
     */
    protected function renderSalesRuleExpiredSince(\DateInterval $diff)
    {
        return __('Is expired since') . ' ' . $this->renderTimeString($diff);
    }

    /**
     * @param \DateInterval $diff
     * @return string
     */
    protected function renderTimeString(\DateInterval $diff)
    {
        if ($diff->days > 0) {
            $dayString = $diff->days == 1 ? __('day') : __('days');
            return $diff->days . ' ' . $dayString;
        }

        if ($diff->i > 0) {
            $minuteString = $diff->i == 1 ? __('minute') : __('minutes');
            return $diff->i . ' ' . $minuteString;
        }

        if ($diff->s > 0) {
            $secondString = $diff->days == 1 ? __('second') : __('seconds');
            return $diff->s . ' ' . $secondString;
        }
    }

    /**
     * @param int $idSalesRule
     * @return array
     */
    protected function getCodePoolNamesBySalesRuleId($idSalesRule)
    {
        $salesRuleConditions = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery::create()
            ->filterByFkSalesrule($idSalesRule)
            ->find();

        $codePoolNames = [];

        /* @var \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCondition $salesRuleCondition */
        foreach ($salesRuleConditions as $salesRuleCondition) {
            if ($salesRuleCondition->getCondition() == self::CONDITION_VOUCHER_CODE_IN_POOL) {
                $configuration = json_decode($salesRuleCondition->getConfiguration());

                /* @var \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepool $codePool */
                $codePool = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery::create()
                    ->filterByIdSalesruleCodepool($configuration->number)
                    ->findOne();

                if ($codePool) {
                    $codePoolNames[] = $codePool->getName();
                }
            }
        }

        return $codePoolNames;
    }
}
