<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Collector\Storage;

use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\Navigation\NavigationConfig;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface;
use Spryker\Zed\Navigation\Persistence\Collector\Propel\NavigationMenuCollectorQuery;

class NavigationMenuCollector extends AbstractStoragePropelCollector
{

    /**
     * @var \Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface
     */
    protected $navigationTreeReader;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface $navigationTreeReader
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        NavigationTreeReaderInterface $navigationTreeReader,
        KeyBuilderInterface $keyBuilder
    ) {
        parent::__construct($utilDataReaderService);

        $this->navigationTreeReader = $navigationTreeReader;
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
        return $this->keyBuilder->generateKey($collectedItemData[NavigationMenuCollectorQuery::FIELD_KEY], $localeName);
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

        $navigationTransfer = $this->navigationTreeReader->findNavigationTree($navigationTransfer, $this->locale);

        return $navigationTransfer->toArray();
    }

}
