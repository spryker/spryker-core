<?php
namespace Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUnitAddressBuilder;
use Generated\Shared\DataBuilder\CountryBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\SpyRegionEntityTransfer;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyUnitAddressLabelDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function haveCompanyUnitAddressTransfer()
    {
        $countryTransfer = $this->haveCountryTransfer();
        $regionTransfer = $this->haveRegionTransfer();
        $companyTransfer = $this->haveCompanyTransfer();
        $companyBusinessUnitTransfer = $this->haveCompanyBusinessUnit();

        $companyUnitAddressTransfer = (new CompanyUnitAddressBuilder())
            ->build();
        $companyUnitAddressTransfer->setFkCompany($companyTransfer->getIdCompany());
        $companyUnitAddressTransfer->setFkCountry($countryTransfer->getIdCountry());
        $companyUnitAddressTransfer->setFkRegion($regionTransfer->getIdRegion());
        $companyUnitAddressTransfer->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        $response = $this->getCompanyUnitAddressFacade()
            ->create($companyUnitAddressTransfer);

        return $response->getCompanyUnitAddressTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function haveCountryTransfer()
    {
        return $this->getCountryFacade()->getCountryByIso2Code(
            (new CountryBuilder())->build()->getIso2Code()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\SpyRegionEntityTransfer
     */
    public function haveRegionTransfer()
    {
        $countryTransfer = $this->haveCountryTransfer();
        $regionEntity = SpyRegionQuery::create()
            ->filterByName('test region')
            ->filterByIso2Code(
                $countryTransfer->getIso2Code()
            )->filterByFkCountry(
                $countryTransfer->getIdCountry()
            )->findOneOrCreate();

        return (new SpyRegionEntityTransfer())
            ->fromArray($regionEntity->toArray());
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveCompanyTransfer()
    {
        $response = $this->getCompanyFacade()
            ->create(
                (new CompanyTransfer())
                    ->setStatus('approved')
                    ->setName('Test company')
                    ->setIsActive(true)
            );

        return $response->getCompanyTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function haveCompanyBusinessUnit()
    {
        $companyTransfer = $this->haveCompanyTransfer();
        $response = $this->getCompanyBusinessUnitFacade()
            ->create(
                (new CompanyBusinessUnitTransfer())
                    ->setName('test business unit')
                    ->setEmail('test@spryker.com')
                    ->setPhone(1234567890)
                    ->setFkCompany($companyTransfer->getIdCompany())
            );

        return $response->getCompanyBusinessUnitTransfer();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface
     */
    public function getCompanyUnitAddressLabelFacade()
    {
        return $this->getLocator()->companyUnitAddressLabel()->facade();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface
     */
    protected function getCompanyUnitAddressFacade()
    {
        return $this->getLocator()->companyUnitAddress()->facade();
    }

    /**
     * @return \Spryker\Zed\Country\Business\CountryFacadeInterface
     */
    protected function getCountryFacade()
    {
        return $this->getLocator()->country()->facade();
    }

    /**
     * @return \Spryker\Zed\Company\Business\CompanyFacadeInterface
     */
    protected function getCompanyFacade()
    {
        return $this->getLocator()->company()->facade();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    protected function getCompanyBusinessUnitFacade()
    {
        return $this->getLocator()->companyBusinessUnit()->facade();
    }
}
