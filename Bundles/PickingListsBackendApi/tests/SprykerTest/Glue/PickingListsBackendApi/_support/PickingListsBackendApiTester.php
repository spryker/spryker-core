<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsBackendApi;

use Codeception\Actor;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication\PickingListItemsBackendResourcePlugin;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication\PickingListsBackendResourcePlugin;

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
class PickingListsBackendApiTester extends Actor
{
    use _generated\PickingListsBackendApiTesterActions;

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
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function createGlueRequestTransfer(): GlueRequestTransfer
    {
        return (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())
            ->setMethod('getCollection'));
    }
}
