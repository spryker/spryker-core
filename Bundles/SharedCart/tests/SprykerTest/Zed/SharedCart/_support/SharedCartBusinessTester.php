<?php
namespace SprykerTest\Zed\SharedCart;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class SharedCartBusinessTester extends Actor
{
    use _generated\SharedCartBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuote(CustomerTransfer $customerTransfer)
    {
        return $this->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUser(CustomerTransfer $customerTransfer)
    {
        return $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserQuotePermissionGroupTransfer
     */
    public function createQuoteCompanyUserPermissionGroupTransfer(CompanyUserTransfer $companyUserTransfer)
    {
        $companyUserQuotePermissionGroupTransfer = new CompanyUserQuotePermissionGroupTransfer();
        $companyUserQuotePermissionGroupTransfer
            ->setCompanyUser($companyUserTransfer) // or just ID
            ->setPermissionGroup(''); // READER/MODIFIER

        return $companyUserQuotePermissionGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserQuotePermissionGroupTransfer $companyUserQuotePermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteShareRequestTransfer
     */
    public function createQuoteShareRequestTransfer(QuoteTransfer $quoteTransfer, CompanyUserQuotePermissionGroupTransfer $companyUserQuotePermissionGroupTransfer)
    {
        $quoteShareRequestTransfer = new QuoteShareRequestTransfer();
        $quoteShareRequestTransfer
            ->setQuote($quoteTransfer)
            ->addCompanyUserQuotePermissionGroup($companyUserQuotePermissionGroupTransfer);

        return $quoteShareRequestTransfer;
    }
}
