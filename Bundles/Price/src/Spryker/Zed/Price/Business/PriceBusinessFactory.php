<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Business;

use Spryker\Zed\Price\Business\Model\BulkWriter;
use Spryker\Zed\Price\Business\Model\Writer;
use Spryker\Zed\Price\Business\Model\Reader;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Price\Business\Model\BulkWriterInterface;
use Spryker\Zed\Price\Persistence\PriceQueryContainer;
use Spryker\Zed\Price\Business\Model\WriterInterface;
use Spryker\Zed\Price\Business\Model\ReaderInterface;
use Spryker\Zed\Price\Dependency\Facade\PriceToProductInterface;
use Spryker\Zed\Price\Dependency\Facade\PriceToTouchInterface;
use Spryker\Zed\Price\Business\Internal\Install;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Price\PriceConfig;
use Spryker\Zed\Price\PriceDependencyProvider;

/**
 * @method PriceConfig getConfig()
 * @method PriceQueryContainer getQueryContainer()
 */
class PriceBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @var PriceQueryContainer
     */
    private $queryContainer;

    /**
     * @var PriceToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var PriceToProductInterface
     */
    protected $productFacade;

    /**
     * @return ReaderInterface
     */
    public function getReaderModel()
    {
        return new Reader(
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return WriterInterface
     */
    public function getWriterModel()
    {
        return new Writer(
            $this->getQueryContainer(),
            $this->getReaderModel(),
            $this->getTouchFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return BulkWriterInterface
     */
    public function getBulkWriterModel()
    {
        return new BulkWriter(
            $this->getQueryContainer(),
            $this->getReaderModel(),
            $this->getTouchFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return PriceToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(PriceDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return PriceToTouchInterface
     */
    protected function getTouchFacade()
    {
        if (empty($this->touchFacade)) {
            $this->touchFacade = $this->getProvidedDependency(PriceDependencyProvider::FACADE_TOUCH);
        }

        return $this->touchFacade;
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return Install
     */
    public function getInstaller(MessengerInterface $messenger)
    {
        $installer = new Install(
            $this->getWriterModel(),
            $this->getConfig()
        );
        $installer->setMessenger($messenger);

        return $installer;
    }

}
