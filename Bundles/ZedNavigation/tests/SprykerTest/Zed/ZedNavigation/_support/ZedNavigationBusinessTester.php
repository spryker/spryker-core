<?php
namespace SprykerTest\Zed\ZedNavigation;

use Codeception\Actor;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ZedNavigationBusinessTester extends Actor
{
    use _generated\ZedNavigationBusinessTesterActions;

   /**
    * Define custom actions here
    */


    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationCollectorMock(): ZedNavigationCollectorInterface
    {
        return $this
            ->getMockBuilder(ZedNavigationCollectorInterface::class)
            ->setMethods(['getNavigation'])
            ->getMock();
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationCacheMock(): ZedNavigationCacheInterface
    {
        $cacheMock = $this
            ->getMockBuilder(ZedNavigationCacheInterface::class)
            ->setMethods(['setNavigation', 'getNavigation', 'hasContent', 'isEnabled'])
            ->getMock();

        $cacheMock->expects($this->never())
            ->method('isEnabled');
        $cacheMock->expects($this->never())
            ->method('setNavigation');
        $cacheMock->expects($this->never())
            ->method('hasContent');

        return $cacheMock;
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\ZedNavigationConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationConfigMock(): ZedNavigationConfig
    {
        return $this
            ->getMockBuilder(ZedNavigationConfig::class)
            ->setMethods(['isNavigationCacheEnabled'])
            ->getMock();
    }
}
