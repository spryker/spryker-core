<?php

namespace Functional\SprykerFeature\Zed\GlossaryDistributor\Business;

use Functional\SprykerFeature\Zed\GlossaryDistributor\Mock\MockQueueFacade;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Zed\Kernel\Business\Factory;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Distributor\Business\DistributorFacade;
use Orm\Zed\Distributor\Persistence\SpyDistributorItem;
use Orm\Zed\Distributor\Persistence\SpyDistributorItemQuery;
use Orm\Zed\Distributor\Persistence\SpyDistributorItemType;
use Orm\Zed\Distributor\Persistence\SpyDistributorItemTypeQuery;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\Factory as PersistenceFactory;
use SprykerFeature\Zed\Distributor\DistributorDependencyProvider;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainer;
use Orm\Zed\Distributor\Persistence\SpyDistributorReceiver;

/**
 * @group SprykerFeature
 * @group Zed
 * @group GlossaryDistributor
 * @group Business
 * @group DistributorFacade
 */
class GlossaryDistributorTest extends AbstractFunctionalTest
{

    /**
     * @var DistributorFacade
     */
    private $distributorFacade;

    /**
     * @var GlossaryFacade
     */
    private $glossaryFacade;

    /**
     * @var LocaleFacade
     */
    private $localeFacade;

    /**
     * @var MockQueueFacade
     */
    private $queueFacade;

    public function setUp()
    {
        parent::setUp();

        $this->queueFacade = new MockQueueFacade();
        $this->distributorFacade = $this->getMockDistributorFacade();
        $this->glossaryFacade = $this->getLocator()->glossary()->facade();
        $this->localeFacade = $this->getLocator()->locale()->facade();
    }

    public function testTouchTranslationItem()
    {
        $localeTransfer = $this->localeFacade->createLocale('xx_XX');
        $this->glossaryFacade->createKey('test.key');
        $this->createReceiver('xx');
        $translationTransfer = $this->glossaryFacade->createTranslation(
            'test.key',
            $localeTransfer,
            'test.translation'
        );
        $oldTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 00:00:00');
        $idGlossaryTranslation = $translationTransfer->getIdGlossaryTranslation();
        $idItemType = $this->createDistributorItemType('glossary_translation', $oldTimestamp);
        $this->createDistributorItem($idItemType, $idGlossaryTranslation, $oldTimestamp);

        $this->distributorFacade->touchItem('glossary_translation', $idGlossaryTranslation);

        $touchItemResult = SpyDistributorItemQuery::create()
            ->filterByFkItemType($idItemType)
            ->filterByFkGlossaryTranslation($idGlossaryTranslation)
            ->filterByTouched($oldTimestamp, Criteria::GREATER_THAN)
            ->count()
        ;

        $this->assertEquals(1, $touchItemResult);
    }

    public function testDistributeTranslationItems()
    {
        $localeTransfer = $this->localeFacade->createLocale('xx_XX');
        $this->glossaryFacade->createKey('test.key');
        $this->createReceiver('xx');
        $translationTransfer = $this->glossaryFacade->createTranslation(
            'test.key',
            $localeTransfer,
            'test.translation'
        );
        $oldTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 00:00:00');
        $this->createDistributorItemType('glossary_translation', $oldTimestamp);
        $idGlossaryTranslation = $translationTransfer->getIdGlossaryTranslation();

        $this->distributorFacade->touchItem('glossary_translation', $idGlossaryTranslation);
        $this->distributorFacade->distributeItems();

        $resultLastDistribution = SpyDistributorItemTypeQuery::create()
            ->filterByTypeKey('glossary_translation')
            ->filterByLastDistribution($oldTimestamp, Criteria::GREATER_THAN)
            ->count()
        ;
        $resultPublishedMessages = $this->queueFacade->getPublishedMessages();

        $this->assertEquals(1, $resultLastDistribution);
        $this->assertArrayHasKey('xx.glossary_translation', $resultPublishedMessages);

        $messagePayload = $resultPublishedMessages['xx.glossary_translation']->getPayload();

        $this->assertArrayHasKey('translation_key', $messagePayload);
        $this->assertArrayHasKey('translation_value', $messagePayload);
        $this->assertArrayHasKey('translation_is_active', $messagePayload);
        $this->assertArrayHasKey('translation_locale', $messagePayload);
        $this->assertEquals('test.key', $messagePayload['translation_key']);
        $this->assertEquals('test.translation', $messagePayload['translation_value']);
        $this->assertEquals('1', $messagePayload['translation_is_active']);
        $this->assertEquals('xx_XX', $messagePayload['translation_locale']);
    }

    /**
     * @return AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @throws PropelException
     *
     * @return int
     */
    private function createDistributorItemType($typeKey, $timestamp)
    {
        $itemType = new SpyDistributorItemType();
        $itemType
            ->setTypeKey($typeKey)
            ->setLastDistribution($timestamp)
            ->save()
        ;

        return $itemType->getIdDistributorItemType();
    }

    /**
     * @param int $idItemType
     * @param int $idGlossaryTranslation
     * @param string $timestamp
     *
     * @throws PropelException
     *
     * @return int
     */
    private function createDistributorItem($idItemType, $idGlossaryTranslation, $timestamp)
    {
        $item = new SpyDistributorItem();
        $item
            ->setFkItemType($idItemType)
            ->setFkGlossaryTranslation($idGlossaryTranslation)
            ->setTouched($timestamp)
            ->save()
        ;

        return $item->getIdDistributorItem();
    }

    /**
     * @param $receiverKey
     *
     * @throws PropelException
     */
    private function createReceiver($receiverKey)
    {
        $receiver = new SpyDistributorReceiver();
        $receiver
            ->setReceiverKey($receiverKey)
            ->save()
        ;
    }

    /**
     * @return DistributorFacade
     */
    private function getMockDistributorFacade()
    {
        $distributorFacade = new DistributorFacade(new Factory('Distributor'), $this->getLocator());
        $container = new Container();
        $container[DistributorDependencyProvider::FACADE_QUEUE] = function () {
            return $this->queueFacade;
        };
        $container[DistributorDependencyProvider::QUERY_EXPANDERS] = function () {
            return [
                $this->getLocator()->glossaryDistributor()->pluginGlossaryQueryExpanderPlugin(),
            ];
        };
        $container[DistributorDependencyProvider::ITEM_PROCESSORS] = function () {
            return [];
        };
        $distributorFacade->setExternalDependencies($container);

        $queryContainer = new DistributorQueryContainer(new PersistenceFactory('Distributor'), $this->getLocator());
        $distributorFacade->setOwnQueryContainer($queryContainer);

        return $distributorFacade;
    }

}
