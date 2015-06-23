<?php

namespace Functional\SprykerFeature\Zed\Distributor\Business;

use Codeception\TestCase\Test;
use Functional\SprykerFeature\Zed\Distributor\Mock\MockQueueFacade;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\QueueMessageTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Pyz\Zed\Glossary\Business\GlossaryFacade;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Distributor\Business\DistributorFacade;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Base\SpyGlossaryKeyQuery;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Base\SpyGlossaryTranslationQuery;
use SprykerFeature\Zed\GlossaryQueue\Business\GlossaryQueueFacade;
use SprykerFeature\Zed\Distributor\DistributorDependencyProvider;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Distributor
 * @group Business
 * @group DistributorFacade
 */
class DistributorFacadeTest extends Test
{

    /**
     * @var DistributorFacade
     */
    protected $distributorFacade;

    public function setUp()
    {
        parent::setUp();
        $this->distributorFacade = new DistributorFacade(new Factory('Distributor'), $this->getLocator());
        $this->distributorFacade->setExternalDependencies($this->createExternalDependency());

    }

    /**
     * @return Container
     */
    private function createExternalDependency()
    {
        $container = new Container();
        $container[DistributorDependencyProvider::FACADE_QUEUE] = function () {
            return new MockQueueFacade();
        };

        return $container;
    }

    /**
     * @return AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return QueueMessageTransfer
     */
    private function getQueueMessage()
    {
        return new QueueMessageTransfer();
    }
}
