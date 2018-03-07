<?php

namespace SprykerTest\Zed\Company\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;

class CompanyHelper extends Module
{
    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    public function findCompanyById(int $idCompany)
    {
        $entity = SpyCompanyQuery::create()
            ->filterByIdCompany($idCompany)
            ->findOne();

        if ($entity !== null) {
            return (new CompanyTransfer())->fromArray($entity->toArray(), true);
        }

        return null;
    }
}
