<?php

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitCreator;


use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CompanyBusinessUnitCreator implements  CompanyBusinessUnitCreatorInterface
{
    use TransactionTrait;

    /**
     * @var CompanyBusinessUnitEntityManagerInterface
     */
    protected $companyBusinessUnitEntityManager;

    /**
     * @var CompanyBusinessUnitConfig
     */
    protected $companyBusinessUnitConfig;

    public function __construct(
        CompanyBusinessUnitEntityManagerInterface $companyBusinessUnitEntityManager,
        CompanyBusinessUnitConfig $companyBusinessUnitConfig
    ) {
        $this->companyBusinessUnitEntityManager = $companyBusinessUnitEntityManager;
        $this->companyBusinessUnitConfig = $companyBusinessUnitConfig;
    }

    /**
     * @param CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return CompanyBusinessUnitResponseTransfer
     */
    public function create(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitResponseTransfer = (new CompanyBusinessUnitResponseTransfer())
            ->setCompanyBusinessUnitTransfer($companyBusinessUnitTransfer)
            ->setIsSuccessful(true);

        return $this->getTransactionHandler()->handleTransaction(function () use ($companyBusinessUnitResponseTransfer) {
            return $this->executeCreateTransaction($companyBusinessUnitResponseTransfer);
        });
    }

    /**
     * @param CompanyResponseTransfer $companyResponseTransfer
     *
     * @return CompanyResponseTransfer
     */
    public function createByCompany(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();

        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
                ->setFkCompany($companyTransfer->getIdCompany())
                ->setName($this->companyBusinessUnitConfig->getCompanyBusinessUnitDefaultName());

        $companyBusinessUnitResponseTransfer = $this->create($companyBusinessUnitTransfer);

        if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
            return $companyResponseTransfer;
        }

        foreach ($companyBusinessUnitResponseTransfer->getMessages() as $messageTransfer) {
            $companyResponseTransfer->addMessage($messageTransfer);
        }

        return $companyResponseTransfer;
    }

    /**
     * @param CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer
     *
     * @return CompanyBusinessUnitResponseTransfer
     */
    protected function executeCreateTransaction(CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitTransfer = $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer = $this->companyBusinessUnitEntityManager->saveCompanyBusinessUnit($companyBusinessUnitTransfer);
        $companyBusinessUnitResponseTransfer->setCompanyBusinessUnitTransfer($companyBusinessUnitTransfer);

        return $companyBusinessUnitResponseTransfer;
    }

}