<?php

namespace SprykerFeature\Zed\Distributor\Business\Internal;

use SprykerFeature\Zed\Distributor\Business\Writer\ItemTypeWriterInterface;
use SprykerFeature\Zed\Distributor\Business\Writer\ReceiverWriterInterface;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;

class ReceiverInstaller extends AbstractInstaller
{

    /**
     * @var array
     */
    protected $configuredReceiver;

    /**
     * @var ItemTypeWriterInterface
     */
    protected $receiverWriter;

    /**
     * @var DistributorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param array $configuredReceiver
     * @param ReceiverWriterInterface $receiverWriter
     * @param DistributorQueryContainerInterface $queryContainer
     */
    public function __construct(
        array $configuredReceiver,
        ReceiverWriterInterface $receiverWriter,
        DistributorQueryContainerInterface $queryContainer
    ) {
        $this->configuredReceiver = $configuredReceiver;
        $this->receiverWriter = $receiverWriter;
        $this->queryContainer = $queryContainer;
    }

    /**
     */
    public function install()
    {
        $existingReceivers = $this->queryContainer->queryReceivers()->find();
        $newReceivers = array_diff($this->configuredReceiver, $existingReceivers);

        foreach ($newReceivers as $receiver) {
            $this->receiverWriter->create($receiver);
        }
    }

}
