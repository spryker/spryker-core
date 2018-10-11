<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MultiCartDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class MultiCartDataImportCommunicationTester extends Actor
{
    use _generated\MultiCartDataImportCommunicationTesterActions;

    /**
     * @param array $seeds
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function createCustomer(array $seeds = []): CustomerResponseTransfer
    {
        $customerTransfer = $this->haveCustomer()
            ->fromArray($seeds, true);
        return $this->getCustomerFacade()->updateCustomer($customerTransfer);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getLocator()->customer()->facade();
    }
}
