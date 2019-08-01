<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-01
 * Time: 12:08
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Creator;


use Generated\Shared\Transfer\PriceProductScheduleErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class PriceProductScheduleCreator implements PriceProductScheduleCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface
     */
    protected $priceProductScheduleWriter;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface
     */
    protected $priceProductScheduleApplierByProductTypeResolver;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    public function createAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): PriceProductScheduleResponseTransfer {
            return $this->executeCreateLogicTransaction($priceProductScheduleTransfer);
        });
    }

    protected function executeCreateLogicTransaction(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer
    {
        $priceProductScheduleTransfer = $this->priceProductScheduleWriter->savePriceProductSchedule($priceProductScheduleTransfer);
        $priceProductScheduleResponseTransfer = (new PriceProductScheduleResponseTransfer())
            ->setPriceProductSchedule($priceProductScheduleTransfer);
        $this->priceProductScheduleApplierByProductTypeResolver
            ->applyPriceProductScheduleByProductType($priceProductScheduleTransfer);

        if ($priceProductScheduleTransfer->getIdPriceProductSchedule() !== null) {
            return $priceProductScheduleResponseTransfer->setIsSuccess(true);
        }

        $priceProductScheduleResponseTransfer->setIsSuccess(false);

        return $this->addErrorMessage($priceProductScheduleResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer $priceProductScheduleResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    protected function addErrorMessage(PriceProductScheduleResponseTransfer $priceProductScheduleResponseTransfer): PriceProductScheduleResponseTransfer
    {
        $priceProductScheduleErrorTransfer = (new PriceProductScheduleErrorTransfer())
            ->setMessage('Schedule price haven not been saved');

        return $priceProductScheduleResponseTransfer->addError($priceProductScheduleErrorTransfer);
    }
}
