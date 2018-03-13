<?php
namespace SprykerTest\Zed\CompanyUnitAddressLabel\Helper;

use ArrayObject;
use Codeception\Module;
use Exception;
use Generated\Shared\DataBuilder\CompanyUnitAddressBuilder;
use Generated\Shared\DataBuilder\CountryBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelEntityTransfer;
use Generated\Shared\Transfer\SpyRegionEntityTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Propel\Runtime\Exception\EntityNotFoundException;
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
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function haveExistingCompanyUnitAddressTransfer()
    {
        $companyUnitAddressEntity = SpyCompanyUnitAddressQuery::create()
            ->findOne();

        if (empty($companyUnitAddressEntity)) {
            throw new Exception('CompanyUnitAddress entity was not found');
        }

        return (new CompanyUnitAddressTransfer())
            ->fromArray($companyUnitAddressEntity->toArray());
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
                    ->setInitialUserTransfer((new CompanyUserTransfer()))
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
     * @throws \Propel\Runtime\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function haveLabelCollection()
    {
        $queryContainer = $this->getLocator()->companyUnitAddressLabel()->queryContainer();
        $label = $queryContainer->queryCompanyUnitAddressLabelQuery()->findOne();

        if (empty($label)) {
            throw new EntityNotFoundException(
                "
                Label entity is supposed to be in table, but was not found.
                Please import labels before running the test.
                "
            );
        }

        return (new CompanyUnitAddressLabelCollectionTransfer())
            ->setLabels(
                new ArrayObject(
                    [
                        (new SpyCompanyUnitAddressLabelEntityTransfer())
                            ->setName($label->getName())
                            ->setIdCompanyUnitAddressLabel($label->getIdCompanyUnitAddressLabel()),
                    ]
                )
            );
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
