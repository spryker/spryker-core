<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\Navigation\NavigationConfig;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\NavigationCollector\Dependency\Facade\NavigationCollectorToNavigationInterface;
use Spryker\Zed\NavigationCollector\Persistence\Collector\Propel\NavigationMenuCollectorQuery;

class NavigationMenuCollector extends AbstractStoragePropelCollector
{

    /**
     * @var \Spryker\Zed\NavigationCollector\Dependency\Facade\NavigationCollectorToNavigationInterface
     */
    protected $navigationFacade;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\NavigationCollector\Dependency\Facade\NavigationCollectorToNavigationInterface $navigationFacade
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        NavigationCollectorToNavigationInterface $navigationFacade,
        KeyBuilderInterface $keyBuilder
    ) {
        parent::__construct($utilDataReaderService);

        $this->navigationFacade = $navigationFacade;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return NavigationConfig::RESOURCE_TYPE_NAVIGATION_MENU;
    }

    /**
     * @param mixed $data
     * @param string $localeName
     * @param array $collectedItemData
     *
     * @return string
     */
    protected function collectKey($data, $localeName, array $collectedItemData)
    {
        return $this->keyBuilder->generateKey($collectedItemData[NavigationMenuCollectorQuery::FIELD_NAVIGATION_KEY], $localeName);
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($collectItemData[NavigationMenuCollectorQuery::FIELD_ID_NAVIGATION]);

        $navigationTransfer = $this->navigationFacade->findNavigationTree($navigationTransfer, $this->locale);

        return $navigationTransfer->toArray();
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }

}
