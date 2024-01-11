<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsBackendApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PickingListBuilder;
use Generated\Shared\DataBuilder\PickingListItemBuilder;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication\PickingListItemsBackendResourcePlugin;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication\PickingListsBackendResourcePlugin;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication\PickingListStartPickingBackendResourcePlugin;

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
 * @SuppressWarnings(\SprykerTest\Glue\PickingListsBackendApi\PHPMD)
 */
class PickingListsBackendApiTester extends Actor
{
    use _generated\PickingListsBackendApiTesterActions;

    /**
     * @uses \Spryker\Shared\PickingList\PickingListConfig::STATUS_READY_FOR_PICKING
     *
     * @var string
     */
    protected const STATUS_READY_FOR_PICKING = 'ready-for-picking';

    /**
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface
     */
    public function createPickingListsBackendResourcePlugin(): JsonApiResourceInterface
    {
        return new PickingListsBackendResourcePlugin();
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface
     */
    public function createPickingListItemsBackendResourcePlugin(): JsonApiResourceInterface
    {
        return new PickingListItemsBackendResourcePlugin();
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface
     */
    public function createPickingListStartPickingBackendResourcePlugin(): JsonApiResourceInterface
    {
        return new PickingListStartPickingBackendResourcePlugin();
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function createGlueRequestTransfer(): GlueRequestTransfer
    {
        return (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())
            ->setMethod('getCollection'));
    }

    /**
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    public function havePickingListWithPickingListItem(): PickingListTransfer
    {
        $pickingListItemBuilder = (new PickingListItemBuilder())
            ->withOrderItem((new ItemBuilder([ItemTransfer::UUID => md5(random_bytes(16))])));

        $pickingListTransfer = (new PickingListBuilder([
            PickingListTransfer::STATUS => static::STATUS_READY_FOR_PICKING,
        ]))
            ->withWarehouse($this->haveStock()->toArray())
            ->withPickingListItem($pickingListItemBuilder)
            ->build();

        return $this->havePickingList($pickingListTransfer);
    }
}
