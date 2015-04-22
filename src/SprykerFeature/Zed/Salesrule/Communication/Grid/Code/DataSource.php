<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Grid\Code;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Business\Model\Codepool;

class DataSource
{

    /**
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery
     */
    protected function getQuery()
    {
        $request = Request::createFromGlobals();
        return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeQuery::create()
            ->filterByFkSalesruleCodepool(
                $request->query->get(Codepool::ID_CODE_POOL_URL_PARAMETER)
            );
    }

    /**
     * @return array|\PropelObjectCollection
     */
    public function getData()
    {
        $data = parent::getData();
        $data = $this->getUsageInformation($data);

        foreach ($data as $key => $code) {
            $data[$key]['active_sign'] = $data[$key]['is_active'] ? 'icon-check' : 'icon-check-empty';
        }

        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function getUsageInformation($data)
    {
        foreach ($data as $key => $code) {
            $codeUsageEntity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeUsageQuery::create()->findOneByFkSalesruleCode( $data[$key]['id_salesrule_code']);
            if ($codeUsageEntity) {
                $data[$key]['redeeming_order'] = $codeUsageEntity->getOrder()->getIncrementId();
                $data[$key]['order_id'] = $codeUsageEntity->getOrder()->getIdSalesOrder();
            } else {
                $data[$key]['redeeming_order'] = '';
                $data[$key]['order_id'] = '';
            }
        }

        return $data;
    }
}
