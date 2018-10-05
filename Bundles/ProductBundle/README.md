# ProductBundle Module

Product bundles are two or more existing products combined into a new product for store display and sales purposes. Typically bundles consist of concrete products, because all items in the bundle need to be potential order items (i.e. have stock). The new (bundled) product does not physically exist in the bundled state. A bundle, when bought will still be handled as separate items in the order management system.
ProductBundle provides all these product bundle features for combining multiple concrete products to a single one, and selling it.

## Installation

```
composer require spryker/product-bundle
```

The following information describes how to install the newly released ´Product´ module (01/2017).
These instructions are only relevant if you need to add this module to an already installed version of the Framework.
If you have not yet installed the Spryker Framework, ignore these instructions as include this module in all versions released after January 2017.

Make sure you updated modules to:

Calculation: >=2.2.0, ProductOption: >=3.1.0, SalesAggregator: >=3.1.0, Tax: >=3.1.0 ,  Cart: >= 2.4.0.  Those all minor releases are BC.

### Plugin configuration

Plugin configuration is the process of incorporating the module into the project by registering the bundle plugins.

**To Register the bundle's plugins**:

1. In `\Pyz\Zed\Calculation\CalculationDependencyProvider::getCalculatorStack`,
add `CalculateBundlePricePlugin` and position it after the `ExpenseTaxWithDiscountsCalculatorPlugin` or before `GrandTotalWithDiscountsCalculatorPlugin`.

2. In `\Pyz\Zed\Cart\CartDependencyProvider::getCartPreCheckPlugins`,
replace the CheckAvailabilityPlugin with the `CartBundleAvailabilityPreCheckPlugin`.

3. In `\Pyz\Zed\Cart\CartDependencyProvider::getExpanderPlugins`,
add `CartItemWithBundleGroupKeyExpanderPlugin` and position it after `CartItemProductOptionPlugin` or `CartItemPricePlugin` if these options are not used.

4. In `\Pyz\Zed\Cart\CartDependencyProvider::getPostSavePlugins`,
add `CartPostSaveUpdateBundlesPlugin`as the last line.

5. In `\Pyz\Zed\Cart\CartDependencyProvider::getExpanderPlugins`,
add `CartItemWithBundleGroupKeyExpanderPlugin` as the last line.

6. In `\Pyz\Zed\Checkout\CheckoutDependencyProvider::getCheckoutPreConditions`,
add `ProductBundleAvailabilityCheckoutPreConditionPlugin`.

7. In `\Pyz\Zed\Oms\OmsDependencyProvider::getReservationHandlerPlugins`,
add `ProductBundleAvailabilityHandlerPlugin`.

8. In  `\Pyz\Zed\Product\ProductDependencyProvider::getProductConcreteAfterUpdatePlugins`,
add `ProductBundleProductConcreteAfterCreatePlugin`.

9. In `\Pyz\Zed\Product\ProductDependencyProvider::getProductConcreteAfterUpdatePlugins`,
add `ProductBundleProductConcreteAfterUpdatePlugin`.

10. In `\Pyz\Zed\Product\ProductDependencyProvider::getProductConcreteReadPlugins`,
add `ProductBundleProductConcreteReadPlugin`.

11. In `\Spryker\Zed\ProductBundle\Communication\Plugin\Sales\ProductBundleOrderSaverPlugin::saveOrder`,
add `ProductBundleOrderSaverPlugin`.

12. In `\Spryker\Zed\ProductBundle\Communication\Plugin\SalesAggregator\ProductBundlePriceAggregatorPlugin::aggregate`,
add `ProductBundlePriceAggregatorPlugin`.

13. In `\Pyz\Zed\Stock\StockDependencyProvider::getStockUpdateHandlerPlugins`,
add `ProductBundleAvailabilityHandlerPlugin`.

#### Plugin descriptions

- `CalculateBundlePricePlugin` - calculates a bundle price.
- `CartBundleAvailabilityPreCheckPlugin` - a cart pre-check plugin to check bundle availability. It replaces `CheckAvailabilityPlugin`.
- `CartItemWithBundleGroupKeyExpanderPlugin` - a cart expander plugin which extracts bundle items.
- `CartPostSaveUpdateBundlesPlugin` - does a cleanup on unused bundles in a quote.
- `CartItemWithBundleGroupKeyExpanderPlugin` - changes the current item group key to include bundle identifier information.
- `ProductBundleAvailabilityCheckoutPreConditionPlugin` - checks product bundle availability when placing an order (final checkout step).
- `ProductBundleAvailabilityHandlerPlugin` - an availability handler which updates bundle availability every time a bundled item moves to reserved state.
- `ProductBundleProductConcreteAfterUpdatePlugin` - is a plugin which persists the product bundle and is used by the Product Management bundle.
- `ProductBundleProductConcreteReadPlugin` - writes product bundle data into `ProductConcreteTransfer`.
- `ProductBundleOrderSaverPlugin` - saves bundle related information when an order with the bundle is placed.
- `ProductBundlePriceAggregatorPlugin` - aggregates product information for the sale bundle.
- `ProductBundleAvailabilityHandlerPlugin` -  a stock handler plugin which updates a bundle's available stock when a bundle or bundled product changes.

### Database migrations
Database migration is the process of adjusting the DB settings to incorporate the new plugin's activity.

**To configure the database migration**:

1. Create a sequence called spy_product_bundle_pk_seq:
``` 
CREATE SEQUENCE "spy_product_bundle_pk_seq";

CREATE TABLE "spy_product_bundle"
(
    "id_product_bundle" INTEGER NOT NULL,
    "fk_bundled_product" INTEGER NOT NULL,
    "fk_product" INTEGER NOT NULL,
    "quantity" INTEGER DEFAULT 1 NOT NULL,
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id_product_bundle")
);

ALTER TABLE "spy_product_bundle" ADD CONSTRAINT "spy_product_bundle-fk_bundled_product"
    FOREIGN KEY ("fk_bundled_product")
    REFERENCES "spy_product" ("id_product")
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE "spy_product_bundle" ADD CONSTRAINT "spy_product_bundle-fk_product"
    FOREIGN KEY ("fk_product")
    REFERENCES "spy_product" ("id_product")
    ON UPDATE CASCADE
    ON DELETE CASCADE;
```
2. Drop old tables/fields.

```
DROP TABLE IF EXISTS "spy_sales_order_item_bundle_item" CASCADE;`

`ALTER TABLE "spy_sales_order_item_bundle"

  DROP COLUMN "tax_rate",

  DROP COLUMN "bundle_type";
  ```

`DROP TABLE IF EXISTS "spy_product_to_bundle" CASCADE;`

### Yves/Project changes
The following information describes the modifications that need to be done to Yves. You can find the Product module demo implementation and all code for Yves in the current Spryker demoshop.

### Cart
The way the cart stores the quantity of items has changed.

The number of items in the cart is now returned by  `$this->cartClient->getItemCount()`.

**To implement the change to the cart**:

1. In `\Pyz\Yves\Cart\Plugin\Provider\CartServiceProvider:register`, change:
```
public function register(Application $app)
{
    $app['cart.quantity'] = $app->share(function () {
        return $this->getClient()->getItemCount();
    });
}
```
2. Cart operations must be updated to cover product bundle logic as follows:
instead of `CartOperationHandler` use `\Pyz\Yves\Cart\Handler\ProductBundleCartOperationHandler` (you can take this from the demoshop).

3. Twig changes:


  * `src/Pyz/Yves/Cart/Theme/default/cart/parts/cart-item.twig` and `src/Pyz/Yves/Cart/Theme/default/cart/index.twig` - now handle product bundles.
  * Project has received a new module called `ProductBundle` in which the bundle grouper is currently stored. This groups items for presentation in `\Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper`

As result, views where items are displayed have also to be changed in `\Pyz\Yves\Cart\Controller\CartController::indexAction`:

 ```
 $cartItems = $this->getFactory()
           ->createProductBundleGroupper()
           ->getGroupedBundleItems($quoteTransfer->getItems(), $quoteTransfer->getBundleItems());

 ```

### Checkout
The checkout summary step has changed and needs adjustment to the cart item listing as follows:

In `\Pyz\Yves\Checkout\Process\Steps\SummaryStep`, inject the grouper and cart client into the `SummaryStep`, and update `getTemplateVariables` method.

 ```
 /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer)
    {
        return [
            'quoteTransfer' => $quoteTransfer,
            'cartItems' => $this->productBundleGrouper->getGroupedBundleItems(
                $quoteTransfer->getItems(),
                $quoteTransfer->getBundleItems()
            ),
        ];
    }
```

`srs/Pyz/Yves/Checkout/Theme/default/checkout/partials/summary-item.twig`
`src/Pyz/Yves/Checkout/Theme/default/checkout/summary.twig` have new item rendering structures, take samples from the demoshop.

### Customer
The customer controller now uses the product bundle grouper.

**Change the following**
In: `\Pyz\Yves\Customer\Controller\OrderController::getOrderDetailsResponseData` do:
```
$bundleItemGrouper = $this->getFactory()->createProductBundleGroupper();
       $items = $bundleItemGrouper->getGroupedBundleItems(
           $orderTransfer->getItems(),
           $orderTransfer->getBundleItems()
       );

       return [
           'order' => $orderTransfer,
           'items' => $items
       ];
```

Take the new implementation for listing order items, including `src/Pyz/Yves/Customer/Theme/default/order/partials/order-items.twig`.


## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/products/product/product_bundles.html)
