<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartNote;

use Codeception\Actor;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\CartNote\Business\CartNoteFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CartNoteBusinessTester extends Actor
{
    use _generated\CartNoteBusinessTesterActions;

    /**
     * @param int $idSalesOrder
     * @param string|null $cartNote
     *
     * @return void
     */
    public function updateCartNote(int $idSalesOrder, ?string $cartNote): void
    {
        $salesOrderEntity = $this->getSalesOrderQuery()->filterByIdSalesOrder($idSalesOrder)->findOne();
        $salesOrderEntity->setCartNote($cartNote);
        $salesOrderEntity->save();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string|null
     */
    public function findOrderCartNote(int $idSalesOrder): ?string
    {
        return $this->getSalesOrderQuery()->filterByIdSalesOrder($idSalesOrder)->findOne()->getCartNote();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }
}
