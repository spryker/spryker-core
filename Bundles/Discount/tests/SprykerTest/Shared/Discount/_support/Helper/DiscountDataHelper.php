<?php

namespace SprykerTest\Shared\Discount\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\DiscountConfiguratorBuilder;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class DiscountDataHelper extends Module
{

    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\DiscountGeneralTransfer
     */
    public function haveDiscount($override = [])
    {
        $discountFacade = $this->getDiscountFacade();

        $discountConfigurator = (new DiscountConfiguratorBuilder($override))
            ->withDiscountGeneral()
            ->withDiscountCondition()
            ->withDiscountCalculator()
            ->build();

        $this->debugSection('Discount', $discountConfigurator->toArray());
        $discountId = $discountFacade->saveDiscount($discountConfigurator);
        $this->debugSection('Discount Id', $discountId);

        $cleanupModule = $this->getDataCleanupHelper();
        $cleanupModule->_addCleanup(function () use ($discountId) {
            $this->debug('Deleting Discount: ' . $discountId);
            $this->getDiscountQuery()->queryDiscount()->findByIdDiscount($discountId)->delete();
        });

        return $discountConfigurator->getDiscountGeneral();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    private function getDiscountFacade()
    {
        return $this->getLocator()->discount()->facade();
    }

    /**
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainer
     */
    private function getDiscountQuery()
    {
        return $this->getLocator()->discount()->queryContainer();
    }

}
