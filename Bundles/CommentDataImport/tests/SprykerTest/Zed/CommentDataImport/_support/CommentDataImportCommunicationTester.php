<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CommentDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Orm\Zed\Comment\Persistence\Base\SpyCommentTagQuery;
use Orm\Zed\Comment\Persistence\SpyCommentQuery;
use Orm\Zed\Comment\Persistence\SpyCommentThreadQuery;
use Orm\Zed\Comment\Persistence\SpyCommentToCommentTagQuery;

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
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CommentDataImportCommunicationTester extends Actor
{
    use _generated\CommentDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureCommentTablesIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getCommentToCommentTagQuery());
        $this->ensureDatabaseTableIsEmpty($this->getCommentTagQuery());
        $this->ensureDatabaseTableIsEmpty($this->getCommentQuery());
        $this->ensureDatabaseTableIsEmpty($this->getCommentThreadQuery());
    }

    /**
     * @param array $seeds
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function createCustomer(array $seeds = []): CustomerResponseTransfer
    {
        $customerTransfer = $this->haveCustomer()
            ->fromArray($seeds, true);

        return $this->getLocator()->customer()->facade()->updateCustomer($customerTransfer);
    }

    /**
     * @param string $reference
     *
     * @return void
     */
    public function ensureCustomerWithReferenceDoesNotExist(string $reference): void
    {
        $customerFacade = $this->getLocator()->customer()->facade();
        $customerTransfer = $customerFacade->findByReference($reference);

        if ($customerTransfer) {
            $customerFacade->deleteCustomer($customerTransfer);
        }
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentQuery
     */
    protected function getCommentQuery(): SpyCommentQuery
    {
        return SpyCommentQuery::create();
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentThreadQuery
     */
    protected function getCommentThreadQuery(): SpyCommentThreadQuery
    {
        return SpyCommentThreadQuery::create();
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentTagQuery
     */
    protected function getCommentTagQuery(): SpyCommentTagQuery
    {
        return SpyCommentTagQuery::create();
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentToCommentTagQuery
     */
    protected function getCommentToCommentTagQuery(): SpyCommentToCommentTagQuery
    {
        return SpyCommentToCommentTagQuery::create();
    }
}
