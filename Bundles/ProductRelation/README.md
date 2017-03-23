# ProductRelation Bundle

## Installation

```
composer require spryker/product-relation
```

The following information describes how to install the newly released ´Product relations´ bundle (03/2017).

If you have not yet installed the Spryker Framework, ignore these instructions as include this bundle in all versions released after April 2017.

### Plugin configuration

For relation use new `Event` bundle is required, because relations listening to certain events in system.

Add new event subscriber `\Spryker\Zed\ProductRelation\Communication\Plugin\ProductRelationEventSubscriber` to `\Pyz\Service\Event\EventDependencyProvider::getEventSubscribers` subscriber stack, this will add watchers which needed to update related products.

### Collector configuration

Add new collector plugin `\Pyz\Zed\Collector\Communication\Plugin\ProductRelationCollectorPlugin` to `\Pyz\Zed\Collector\CollectorDependencyProvider::provideBusinessLayerDependencies` store `STORAGE_PLUGINS` plugins container. You can grab this collector from latest demoshop.


more tips how it can be configured is in spryker documentation [usage](http://spryker.github.io/core/bundles/product-relation/#usage).

## Documentation

[Documentation](http://spryker.github.io/core/bundles/product-relation)
