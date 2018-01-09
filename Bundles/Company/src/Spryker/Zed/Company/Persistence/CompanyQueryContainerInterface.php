<?php

namespace Spryker\Zed\Company\Persistence;

interface CompanyQueryContainerInterface
{

    /**
     * @param int $idCompany
     *
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    public function queryCompanyById($idCompany);
}