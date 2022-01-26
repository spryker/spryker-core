<?php
/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductOfferShoppingListDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferShoppingListDataImportCommunicationTester extends Actor
{
    use _generated\ProductOfferShoppingListDataImportCommunicationTesterActions;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function deleteShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->getShoppingListFacade()->removeShoppingList($shoppingListTransfer);
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Business\ShoppingListFacadeInterface
     */
    public function getShoppingListFacade(): ShoppingListFacadeInterface
    {
        return $this->getLocator()->shoppingList()->facade();
    }

    /**
     * @return void
     */
    public function ensureShoppingListProductOfferDatabaseTableIsEmpty(): void
    {
        $this->getShoppingListItemQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableIsEmpty(): void
    {
        $shoppingListItemWithProductOfferReferencesQuery = $this->getShoppingListItemQuery();
        $this->assertCount(0, $shoppingListItemWithProductOfferReferencesQuery, 'Found at least one entry in the database table but database table was expected to be empty.');
    }

    /**
     * @return void
     */
    public function assertShoppingListProductOfferDatabaseTableContainsData(): void
    {
        $shoppingListItemWithProductOfferReferencesQuery = $this->getShoppingListItemQuery();
        $this->assertTrue(($shoppingListItemWithProductOfferReferencesQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery
     */
    protected function getShoppingListItemQuery(): SpyShoppingListItemQuery
    {
        return SpyShoppingListItemQuery::create()
            ->filterByProductOfferReference(null, Criteria::ISNOTNULL);
    }
}
