<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Order;

use ArrayObject;
use DateTime;
use Exception;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxAppSaleTransfer;
use Generated\Shared\Transfer\TaxRefundRequestTransfer;
use Spryker\Client\TaxApp\TaxAppClientInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface;
use Spryker\Zed\TaxApp\Business\Config\ConfigReaderInterface;
use Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToSalesFacadeInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;

class RefundProcessor implements RefundProcessorInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Client\TaxApp\TaxAppClientInterface
     */
    protected TaxAppClientInterface $taxAppClient;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface
     */
    protected TaxAppToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToSalesFacadeInterface
     */
    protected TaxAppToSalesFacadeInterface $salesFacade;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface
     */
    protected TaxAppMapperInterface $taxAppMapper;

    /**
     * @var \Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface
     */
    protected AccessTokenProviderInterface $accessTokenProvider;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Config\ConfigReaderInterface
     */
    protected ConfigReaderInterface $configReader;

    /**
     * @var array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface>
     */
    protected array $orderTaxAppExpanderPlugins;

    /**
     * @param \Spryker\Client\TaxApp\TaxAppClientInterface $taxAppClient
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface $taxAppMapper
     * @param \Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface $accessTokenProvider
     * @param \Spryker\Zed\TaxApp\Business\Config\ConfigReaderInterface $configReader
     * @param array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface> $orderTaxAppExpanderPlugins
     */
    public function __construct(
        TaxAppClientInterface $taxAppClient,
        TaxAppToStoreFacadeInterface $storeFacade,
        TaxAppToSalesFacadeInterface $salesFacade,
        TaxAppMapperInterface $taxAppMapper,
        AccessTokenProviderInterface $accessTokenProvider,
        ConfigReaderInterface $configReader,
        array $orderTaxAppExpanderPlugins
    ) {
        $this->taxAppClient = $taxAppClient;
        $this->storeFacade = $storeFacade;
        $this->salesFacade = $salesFacade;
        $this->taxAppMapper = $taxAppMapper;
        $this->accessTokenProvider = $accessTokenProvider;
        $this->configReader = $configReader;
        $this->orderTaxAppExpanderPlugins = $orderTaxAppExpanderPlugins;
    }

    /**
     * @param array<int> $orderItemIds
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function processOrderRefund(array $orderItemIds, int $idSalesOrder): void
    {
        $orderTransfer = $this->createOrderWithItemsToBeRefunded($orderItemIds, $idSalesOrder);

        if (!$orderTransfer) {
            $this->getLogger()->warning(sprintf('Order with ID `%s` not found', $idSalesOrder));

            return;
        }

        try {
            $storeTransfer = $this->storeFacade->getStoreByName($orderTransfer->getStoreOrFail());
        } catch (Exception $e) {
            $this->getLogger()->warning('Store from order not found, using current store instead');
            $storeTransfer = $this->storeFacade->getCurrentStore();
        }

        $taxAppConfigTransfer = $this->configReader->getTaxAppConfigByIdStore($storeTransfer->getIdStoreOrFail());

        if ($taxAppConfigTransfer === null || !$taxAppConfigTransfer->getIsActive()) {
            $this->getLogger()->warning('App is not configured or is not active.');

            return;
        }

        $orderTransfer = $this->executeOrderTaxAppExpanderPlugins($orderTransfer);

        $taxAppSaleTransfer = $this->taxAppMapper->mapOrderTransferToTaxAppSaleTransfer($orderTransfer, new TaxAppSaleTransfer());

        $taxRefundRequestTransfer = new TaxRefundRequestTransfer();
        $taxRefundRequestTransfer->setSale($taxAppSaleTransfer);
        $taxRefundRequestTransfer->setReportingDate((new DateTime())->format('Y-m-d'));

        $taxRefundRequestTransfer = $this->expandTaxRefundRequestWithAccessToken($taxRefundRequestTransfer);

        $this->taxAppClient->requestTaxRefund($taxRefundRequestTransfer, $taxAppConfigTransfer, $storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxRefundRequestTransfer $taxRefundRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TaxRefundRequestTransfer
     */
    protected function expandTaxRefundRequestWithAccessToken(
        TaxRefundRequestTransfer $taxRefundRequestTransfer
    ): TaxRefundRequestTransfer {
        $taxRefundRequestTransfer->setAuthorization($this->accessTokenProvider->getAccessToken());

        return $taxRefundRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function executeOrderTaxAppExpanderPlugins(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($this->orderTaxAppExpanderPlugins as $orderTaxAppExpanderPlugin) {
            $orderTransfer = $orderTaxAppExpanderPlugin->expand($orderTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @param array<int> $orderItemIds
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    protected function createOrderWithItemsToBeRefunded(array $orderItemIds, int $idSalesOrder): ?OrderTransfer
    {
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($idSalesOrder);

        if (!$orderTransfer) {
            return null;
        }

        $newOrderItems = [];
        foreach ($orderTransfer->getItems() as $item) {
            $itemId = $item->getIdSalesOrderItemOrFail();

            if (in_array($itemId, $orderItemIds)) {
                $newOrderItems[] = $item;
            }
        }

        $orderTransfer->setItems(new ArrayObject($newOrderItems));

        return $orderTransfer;
    }
}
