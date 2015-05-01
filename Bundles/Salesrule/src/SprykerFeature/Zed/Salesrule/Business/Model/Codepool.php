<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model;

use SprykerFeature\Shared\Salesrule\Transfer\CodePool as CodePoolTransfer;
use SprykerFeature\Zed\Library\Copy;

class Codepool
{

    const MAX_COLLISION_COUNT = 10;
    const ID_CODE_POOL_URL_PARAMETER = 'id-code-pool';
    const ID_CODE_POOL = 'id_code_pool';


    /**
     * @param int $codepoolId
     * @param int $amount
     * @param int $length
     * @param string $prefix
     * @param int $customerId
     * @param bool $isActive
     * @return bool
     */
    public function createCodes($codepoolId, $amount, $length = 6, $prefix = null, $customerId = null, $isActive = true)
    {
        if ($amount < 1) {
            $amount = 1;
        }

        if (pow(strlen($this->factory->createSettings()->getAllowedCodeAlphabet()), $length) < $amount) {
            return false;
        }

        for ($i = 0; $i < $amount; $i++) {
            $this->createCode($codepoolId, null, $length, $prefix, $customerId, $isActive);
        }
        return true;
    }

    /**
     * @param $codepoolId
     * @param null $code
     * @param int $length
     * @param null $prefix
     * @param null $customerId
     * @param bool $isActive
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCode
     */
    public function createCode($codepoolId, $code = null, $length = 6, $prefix = null, $customerId = null, $isActive = true)
    {
        $codeEntity = new \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCode();
        $codeEntity->setFkSalesruleCodepool($codepoolId);

        if (null === $code) {
            $code = $this->generateUniqueCode($length, $prefix);
        }

        $codeEntity->setCode($code);
        $codeEntity->setFkCustomer($customerId);
        $codeEntity->setIsActive($isActive);
        $codeEntity->save();
        return $codeEntity;
    }

    /**
     * @param $length
     * @param $prefix
     * @return string
     * @throws \Exception
     */
    protected function generateUniqueCode($length, $prefix)
    {
        $collisionCounter = 0;

        do {
            $code = '';
            if ($prefix) {
                $code .= $prefix;

            }
            $code .= substr(str_shuffle(str_repeat($this->factory->createSettings()->getAllowedCodeAlphabet(), 2)), 0, $length);
            $collisionCounter++;

            if ($collisionCounter > self::MAX_COLLISION_COUNT) {
                throw new \Exception('Could not generate an new unique code as too many collisions occured!');
            }

        } while ($this->getSalesruleCodeByCode($code));

        return $code;
    }

    /**
     * @param string $code
     * @return bool
     */
    public function getSalesruleCodeByCode($code)
    {
        return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeQuery::create()->findOneByCode($code);
    }

    /**
     * @param int $codepoolId
     * @return string
     */
    public function getSalesruleCodePrefixByCodepoolId($codepoolId)
    {
        $codepool = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery::create()->findPk($codepoolId);
        return $codepool->getPrefix();
    }

    /**
     * @param int $codepoolId
     * @return array|mixed|PropelObjectCollection
     */
    protected function getSalesruleCodes($codepoolId)
    {
        return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeQuery::create()->filterByFkSalesruleCodepool($codepoolId)->find();
    }

    /**
     * @param int $codepoolId
     * @return bool
     */
    public function canDeleteSalesruleCodepool($codepoolId)
    {
        foreach ($this->getSalesruleCodes($codepoolId) as $code) {
            /* @var \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCode $code */
            if (!$this->facadeSalesrule->canDeleteSalesruleCode($code->getPrimaryKey())) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $codepoolId
     * @return mixed
     */
    public function deleteCodepool($codepoolId)
    {
        \Propel\Runtime\Propel::getConnection()->beginTransaction();

        $codes = $this->getSalesruleCodes($codepoolId);
        $amount = $codes->count();

        foreach ($codes as $code) {
            /* @var \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCode $code */
            $code->delete();
        }

        $codepoolEntity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery::create()->findPk($codepoolId);
        $codepoolEntity->delete();
        \Propel\Runtime\Propel::getConnection()->commit();
        return $amount;
    }

    /**
     * @param string $prefix
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepool
     */
    public function getSalesruleByPrefix($prefix)
    {
        return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery::create()->findOneByPrefix($prefix);
    }

    /**
     * @param CodePoolTransfer $codePool
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepool
     */
    public function saveCodePool(CodePoolTransfer $codePool)
    {
        $idCodePool = $codePool->getIdSalesruleCodepool() ?: null;
        $codePool->setIdSalesruleCodepool($idCodePool);

        $entity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery::create()
            ->filterByIdSalesruleCodepool($idCodePool)->findOneOrCreate();

        Copy::transferToEntityNoNullValues($codePool, $entity);
        $entity->save();
        return $entity;
    }
}
