<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PickingListPushNotification\Business\Creator\PushNotificationCreator;
use Spryker\Zed\PickingListPushNotification\Business\Creator\PushNotificationCreatorInterface;
use Spryker\Zed\PickingListPushNotification\Business\Extractor\PickingListExtractor;
use Spryker\Zed\PickingListPushNotification\Business\Extractor\PickingListExtractorInterface;
use Spryker\Zed\PickingListPushNotification\Business\Filter\PickingListFilter;
use Spryker\Zed\PickingListPushNotification\Business\Filter\PickingListFilterInterface;
use Spryker\Zed\PickingListPushNotification\Business\Generator\PushNotificationPayloadGenerator;
use Spryker\Zed\PickingListPushNotification\Business\Generator\PushNotificationPayloadGeneratorInterface;
use Spryker\Zed\PickingListPushNotification\Business\Grouper\PickingListGrouper;
use Spryker\Zed\PickingListPushNotification\Business\Grouper\PickingListGrouperInterface;
use Spryker\Zed\PickingListPushNotification\Business\Grouper\WarehouseUserAssignmentGrouper;
use Spryker\Zed\PickingListPushNotification\Business\Grouper\WarehouseUserAssignmentGrouperInterface;
use Spryker\Zed\PickingListPushNotification\Business\Reader\WarehouseUserAssignmentReader;
use Spryker\Zed\PickingListPushNotification\Business\Reader\WarehouseUserAssignmentReaderInterface;
use Spryker\Zed\PickingListPushNotification\Business\Validator\PushNotificationSubscriptionWarehouseUserAssignmentValidator;
use Spryker\Zed\PickingListPushNotification\Business\Validator\PushNotificationSubscriptionWarehouseUserAssignmentValidatorInterface;
use Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToPushNotificationFacadeInterface;
use Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToWarehouseUserFacadeInterface;
use Spryker\Zed\PickingListPushNotification\PickingListPushNotificationDependencyProvider;

/**
 * @method \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig getConfig()
 */
class PickingListPushNotificationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PickingListPushNotification\Business\Creator\PushNotificationCreatorInterface
     */
    public function createPushNotificationCreator(): PushNotificationCreatorInterface
    {
        return new PushNotificationCreator(
            $this->createPickingListFilter(),
            $this->createPickingListGrouper(),
            $this->getConfig(),
            $this->createPushNotificationPayloadGenerator(),
            $this->getPushNotificationFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\Business\Validator\PushNotificationSubscriptionWarehouseUserAssignmentValidatorInterface
     */
    public function createPushNotificationSubscriptionWarehouseUserAssignmentValidator(): PushNotificationSubscriptionWarehouseUserAssignmentValidatorInterface
    {
        return new PushNotificationSubscriptionWarehouseUserAssignmentValidator(
            $this->createWarehouseUserAssignmentReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\Business\Grouper\PickingListGrouperInterface
     */
    public function createPickingListGrouper(): PickingListGrouperInterface
    {
        return new PickingListGrouper();
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\Business\Grouper\WarehouseUserAssignmentGrouperInterface
     */
    public function createWarehouseUserAssignmentGrouper(): WarehouseUserAssignmentGrouperInterface
    {
        return new WarehouseUserAssignmentGrouper();
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\Business\Generator\PushNotificationPayloadGeneratorInterface
     */
    public function createPushNotificationPayloadGenerator(): PushNotificationPayloadGeneratorInterface
    {
        return new PushNotificationPayloadGenerator(
            $this->createPickingListExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\Business\Extractor\PickingListExtractorInterface
     */
    public function createPickingListExtractor(): PickingListExtractorInterface
    {
        return new PickingListExtractor();
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\Business\Filter\PickingListFilterInterface
     */
    public function createPickingListFilter(): PickingListFilterInterface
    {
        return new PickingListFilter($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\Business\Reader\WarehouseUserAssignmentReaderInterface
     */
    public function createWarehouseUserAssignmentReader(): WarehouseUserAssignmentReaderInterface
    {
        return new WarehouseUserAssignmentReader(
            $this->getWarehouseUserFacade(),
            $this->createWarehouseUserAssignmentGrouper(),
        );
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToWarehouseUserFacadeInterface
     */
    public function getWarehouseUserFacade(): PickingListPushNotificationToWarehouseUserFacadeInterface
    {
        return $this->getProvidedDependency(PickingListPushNotificationDependencyProvider::FACADE_WAREHOUSE_USER);
    }

    /**
     * @return \Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToPushNotificationFacadeInterface
     */
    public function getPushNotificationFacade(): PickingListPushNotificationToPushNotificationFacadeInterface
    {
        return $this->getProvidedDependency(PickingListPushNotificationDependencyProvider::FACADE_PUSH_NOTIFICATION);
    }
}
