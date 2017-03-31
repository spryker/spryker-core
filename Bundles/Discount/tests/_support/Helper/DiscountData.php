<?php
namespace Discount\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\DiscountConfiguratorBuilder;
use Testify\Helper\DataCleanup;
use Testify\Helper\Locator;

class DiscountData extends Module
{

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

        if ($this->hasModule('\\' . DataCleanup::class)) {
            $cleanupModule = $this->getDataCleanupModule();
            $cleanupModule->_addCleanup(function () use ($discountId) {
                $this->debug("Deleting Discount: $discountId");
                $this->getDiscountQuery()->queryDiscount()->findByIdDiscount($discountId)->delete();
            });
        }
        return $discountConfigurator->getDiscountGeneral();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    private function getDiscountFacade()
    {
        return $this->getModule('\\' . Locator::class)->getLocator()->discount()->facade();
    }

    /**
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainer
     */
    private function getDiscountQuery()
    {
        return $this->getModule('\\' . Locator::class)->getLocator()->product()->queryContainer();
    }

    /**
     * @return \Testify\Helper\DataCleanup
     */
    protected function getDataCleanupModule()
    {
        return $this->getModule('\\' . DataCleanup::class);
    }

}
