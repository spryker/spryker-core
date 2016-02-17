<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Discount\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Discount\Business\Model\CollectorResolver;
use Generated\Shared\Transfer\DiscountTransfer;

class CollectorResolverTest extends Test
{

    const COLLECTOR_1 = 'COLLECTOR_1';
    const COLLECTOR_2 = 'COLLECTOR_2';

    /**
     * @return void
     */
    public function testWhenANDConditionUsedWithCollectorsProvidingDifferentItemsThenNoItemsReturned()
    {
        $quoteTransfer = $this->buildQuoteTransfer();

        $collectorPickedItem1 = $quoteTransfer->getItems()[0];
        $collectorPickedItem2 = $quoteTransfer->getItems()[2];

        $collectors = [];
        $collectors[self::COLLECTOR_1] = $this->createCollectorPluginMock([$collectorPickedItem1]);
        $collectors[self::COLLECTOR_2] = $this->createCollectorPluginMock([$collectorPickedItem2]);

        $collectorResolver = $this->createCollectorResolver($collectors);

        $discountTransfer = $this->createDiscountTransfer();
        $discountTransfer->setCollectorLogicalOperator(CollectorResolver::OPERATOR_AND);

        $collectedItems = $collectorResolver->collectItems($quoteTransfer, $discountTransfer);

        $this->assertCount(0, $collectedItems);
    }

    /**
     * @return void
     */
    public function testWhenANDConditionUsedWithCollectorsProvidingSameItemsThenMatchedItemReturned()
    {
        $quoteTransfer = $this->buildQuoteTransfer();

        $collectorPickedItem1 = $quoteTransfer->getItems()[0];

        $collectors = [];
        $collectors[self::COLLECTOR_1] = $this->createCollectorPluginMock([$collectorPickedItem1]);
        $collectors[self::COLLECTOR_2] = $this->createCollectorPluginMock([$collectorPickedItem1]);

        $collectorResolver = $this->createCollectorResolver($collectors);

        $discountTransfer = $this->createDiscountTransfer();
        $discountTransfer->setCollectorLogicalOperator(CollectorResolver::OPERATOR_AND);

        $collectedItems = $collectorResolver->collectItems($quoteTransfer, $discountTransfer);

        $this->assertCount(1, $collectedItems);
    }

    /**
     * @return void
     */
    public function testWhenORConditionUsedWithDifferentItemsThenItShouldReturnAllCollectorItems()
    {
        $quoteTransfer = $this->buildQuoteTransfer();

        $collectorPickedItem1 = $quoteTransfer->getItems()[0];
        $collectorPickedItem2 = $quoteTransfer->getItems()[2];

        $collectors = [];
        $collectors[self::COLLECTOR_1] = $this->createCollectorPluginMock([$collectorPickedItem1]);
        $collectors[self::COLLECTOR_2] = $this->createCollectorPluginMock([$collectorPickedItem2]);

        $collectorResolver = $this->createCollectorResolver($collectors);

        $discountTransfer = $this->createDiscountTransfer();
        $discountTransfer->setCollectorLogicalOperator(CollectorResolver::OPERATOR_OR);

        $collectedItems = $collectorResolver->collectItems($quoteTransfer, $discountTransfer);

        $this->assertCount(2, $collectedItems);
    }

    /**
     * @return void
     */
    public function testWhenFirstCollectorEmptyAndANDConditionUsedShouldBeNoItemsCollected()
    {
        $quoteTransfer = $this->buildQuoteTransfer();

        $collectors = [];
        $collectors[self::COLLECTOR_1] = $this->createCollectorPluginMock([]);

        $collectorResolver = $this->createCollectorResolver($collectors);

        $discountTransfer = $this->createDiscountTransfer();
        $discountTransfer->setCollectorLogicalOperator(CollectorResolver::OPERATOR_AND);

        $collectedItems = $collectorResolver->collectItems($quoteTransfer, $discountTransfer);

        $this->assertCount(0, $collectedItems);
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    protected function createDiscountTransfer()
    {
        $discountTransfer = new DiscountTransfer();

        $discountCollectors = new \ArrayObject();
        $discountCollectorTransfer = $this->createDiscountCollectorTransfer();
        $discountCollectorTransfer->setCollectorPlugin(self::COLLECTOR_1);
        $discountCollectors->append($discountCollectorTransfer);

        $discountCollectorTransfer = $this->createDiscountCollectorTransfer();
        $discountCollectorTransfer->setCollectorPlugin(self::COLLECTOR_2);
        $discountCollectors->append($discountCollectorTransfer);

        $discountTransfer->setDiscountCollectors($discountCollectors);

        return $discountTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildQuoteTransfer()
    {
        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->addItem($this->createItemTransfer('SKU-123'));
        $quoteTransfer->addItem($this->createItemTransfer('SKU-321'));
        $quoteTransfer->addItem($this->createItemTransfer('SKU-111'));
        $quoteTransfer->addItem($this->createItemTransfer('SKU-222'));

        return $quoteTransfer;
    }

    /**
     * @param array $collectedItems
     *
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createCollectorPluginMock(array $collectedItems)
    {
        $collectorPluginMock = $this
            ->getMockBuilder('\Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $collectorPluginMock->method('collect')->willReturn($collectedItems);

        return $collectorPluginMock;
    }

    /**
     * @param array $collectorPlugins
     *
     * @return \Spryker\Zed\Discount\DiscountConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getDiscountCollectorConfigurator(array $collectorPlugins)
    {
        $discountConfigMock = $this
            ->getMockBuilder('\Spryker\Zed\Discount\DiscountConfigInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $i = 0;
        foreach ($collectorPlugins as $idCollector => $collector) {
            $discountConfigMock
                ->expects($this->at($i++))
                ->method('getCollectorPluginByName')
                ->with($this->equalTo($idCollector))
                ->willReturn($collector);
        }

        return $discountConfigMock;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer($sku)
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($sku);

        return $itemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountCollectorTransfer
     */
    protected function createDiscountCollectorTransfer()
    {
        return new DiscountCollectorTransfer();
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface[] $collectorPlugins
     *
     * @return \Spryker\Zed\Discount\Business\Model\CollectorResolver
     */
    protected function createCollectorResolver(array $collectorPlugins = [])
    {
        return new CollectorResolver($collectorPlugins);
    }

}
