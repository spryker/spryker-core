<?php

namespace SprykerFeature\Zed\Distributor\Business\Internal;

use SprykerFeature\Zed\Distributor\Business\Writer\ItemTypeWriterInterface;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;

class ItemTypeInstaller extends AbstractInstaller
{

    /**
     * @var array
     */
    protected $configuredItemTypes;

    /**
     * @var ItemTypeWriterInterface
     */
    protected $itemTypeWriter;

    /**
     * @var DistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param array $configuredItemTypes
     * @param ItemTypeWriterInterface $itemTypeWriter
     * @param DistributorQueryContainerInterface $queryContainer
     */
    public function __construct(
        array $configuredItemTypes,
        ItemTypeWriterInterface $itemTypeWriter,
        DistributorQueryContainerInterface $queryContainer
    ) {
        $this->configuredItemTypes = $configuredItemTypes;
        $this->itemTypeWriter = $itemTypeWriter;
        $this->queryContainer = $queryContainer;
    }

    /**
     */
    public function install()
    {
        $existingItemTypes = $this->queryContainer->queryItemTypes()->find();
        $newItemTypes = array_diff($this->configuredItemTypes, $existingItemTypes);

        foreach ($newItemTypes as $itemType) {
            $this->itemTypeWriter->create($itemType);
        }
    }

}
