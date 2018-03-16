<?php


namespace Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade;


use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;

class CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeBridge implements CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
{

    /** @var CompanyBusinessUnitFacadeInterface */
    protected $companyBusinessUnitFacade;

    /**
     * @param CompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct($companyBusinessUnitFacade)
    {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /** @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer
    {
        return $this->companyBusinessUnitFacade->getCompanyBusinessUnitById($companyBusinessUnitTransfer);
    }

    /** @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function update(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer
    {
        return $this->companyBusinessUnitFacade->update($companyBusinessUnitTransfer);
    }
}