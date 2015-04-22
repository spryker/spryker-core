<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model;

use SprykerFeature\Zed\Salesrule\Business\Downloader\Downloader;

class Code
{

    const COUPON_CODE_CONDITION = 'ConditionVoucherCodeInPool';

    /**
     * @param int $codeId
     */
    public function deleteCode($codeId)
    {
        $entity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeQuery::create()->findPk($codeId);
        if ($entity) {
            $entity->delete();
        }
    }

    /**
     * @param int $codeId
     * @return bool
     */
    public function canDeleteSalesruleCode($codeId)
    {
        $result = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeUsageQuery::create()->filterByFkSalesruleCode($codeId)->find();

        return $result->count() == 0;
    }

    /**
     * Checks if a salesrule condition exists which requires the code to be in a certain codepool
     *
     * @param string $code
     * @return bool
     */
    public function isCodeInActiveSalesruleCondition($code)
    {
        $salesrules = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleQuery::create()
            ->filterByIsActive(true)
            ->useSalesruleConditionQuery()
                ->filterByCondition(self::COUPON_CODE_CONDITION, \Propel\Runtime\ActiveQuery\Criteria::EQUAL)
            ->endUse()
            ->find();

        foreach ($salesrules as $salesrule) {
            /* @var \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule */
            $conditions = $salesrule->getSalesruleConditions();

            foreach ($conditions as $condition) {
                if ($condition->getCondition() == self::COUPON_CODE_CONDITION) {
                    $config = (array) json_decode(($condition->getConfiguration()));
                    if ($this->isCodeInCodePool($code, $config['number'])) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param string $code
     * @param int $codepoolId
     * @return bool
     */
    public function isCodeInCodePool($code, $codepoolId)
    {
        $codeEntity = $this->factory->createModelFinder()->getCouponCodeByCode($code);

        return ($codeEntity)? $codepoolId == $codeEntity->getCodepool()->getIdSalesruleCodepool() : false;
    }

    /**
     * @param $fkSalesRuleCodePool
     */
    public function downloadCodesFromCodePool($fkSalesRuleCodePool)
    {
        $codes = $this
            ->prepareAndExecuteQuery(
                'codes-query',
                $this->getSelectString($fkSalesRuleCodePool)
            )
            ->fetchAll(\PDO::FETCH_ASSOC)
        ;

        $pathToFile = sys_get_temp_dir() . '/codepool_' . $fkSalesRuleCodePool . '.csv';
        $csvFile = new CsvFile($pathToFile);
        $csvFile->writeRow(['code', 'active', 'is_used']);

        foreach ($codes as $code) {
            $data = [
                $code['code'],
                $code['is_active'],
                ($code['id_salesrule_code_usage']) ? 1 : 0,
            ];
            $csvFile->writeRow($data);
        }
        $downloader = new Downloader($pathToFile);
        $downloader->download();
        unset($pathToFile);
    }

    /**
     * @param $fkSalesRuleCodePool
     * @return string
     */
    private function getSelectString($fkSalesRuleCodePool)
    {
        return 'SELECT * FROM spy_salesrule_code '
            . 'LEFT JOIN spy_salesrule_code_usage ON spy_salesrule_code.id_salesrule_code = spy_salesrule_code_usage.fk_salesrule_code '
            . 'WHERE spy_salesrule_code.fk_salesrule_codepool = ' . $fkSalesRuleCodePool . ' '
            . 'GROUP BY spy_salesrule_code.code;';
    }
}
