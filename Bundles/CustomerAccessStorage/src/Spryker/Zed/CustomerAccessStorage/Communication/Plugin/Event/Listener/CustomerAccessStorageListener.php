<?php

namespace Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Event\Listener;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Communication\CustomerAccessStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessStorageFacadeInterface getFacade()
 */
class CustomerAccessStorageListener extends AbstractPlugin implements EventHandlerInterface
{
    use TransactionTrait;

    /**
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $eventTransfer, $eventName)
    {
        if (Propel::getConnection()->inTransaction()) {
            throw new PropelException('This operation is not allowed inside of transaction');
        }

        $this->getFacade()->publish();
    }
}
