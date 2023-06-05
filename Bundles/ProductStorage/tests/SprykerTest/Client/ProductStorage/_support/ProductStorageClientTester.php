<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductStorageClientTester extends Actor
{
    use _generated\ProductStorageClientTesterActions;

    /**
     * @uses \Spryker\Shared\ProductStorage\ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_RESOURCE_NAME = 'product_abstract';

    /**
     * @uses \Spryker\Shared\ProductStorage\ProductStorageConstants::PRODUCT_CONCRETE_RESOURCE_NAME
     *
     * @var string
     */
    public const PRODUCT_CONCRETE_RESOURCE_NAME = 'product_concrete';

    /**
     * @var string
     */
    public const LOCALE_NAME = 'de_de';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_CONCRETE_ID = 777;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'de';

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getLocator()
            ->productStorage()
            ->client();
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function createProductViewTransfer(array $seed = []): ProductViewTransfer
    {
        return (new ProductViewTransfer())
            ->fromArray($seed, true)
            ->setAttributeMap(
                (new AttributeMapStorageTransfer())->setProductConcreteIds([static::TEST_PRODUCT_CONCRETE_ID]),
            );
    }

    /**
     * @param list<int> $productIds
     * @param string $productResourceName
     *
     * @return void
     */
    public function createProductViewTransfersInStorage(array $productIds, string $productResourceName): void
    {
        $fieldName = ProductViewTransfer::ID_PRODUCT_ABSTRACT;
        $keyBase = sprintf('%s:%s:%s', $productResourceName, static::STORE_NAME_DE, static::LOCALE_NAME);

        if ($productResourceName === static::PRODUCT_CONCRETE_RESOURCE_NAME) {
            $fieldName = ProductViewTransfer::ID_PRODUCT_CONCRETE;
            $keyBase = sprintf('%s:%s', $productResourceName, static::LOCALE_NAME);
        }

        foreach ($productIds as $idProduct) {
            $productViewTransfer = $this->createProductViewTransfer([$fieldName => $idProduct]);

            $key = sprintf('%s:%d', $keyBase, $idProduct);
            $this->setToStorage($key, $productViewTransfer->toArray());
        }
    }

    /**
     * @param string $key
     * @param array<string, mixed> $data
     *
     * @return void
     */
    protected function setToStorage(string $key, array $data): void
    {
        $this->getLocator()->storage()->client()->set($key, $this->encodeJson($data));
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return string|null
     */
    protected function encodeJson(array $data): ?string
    {
        return $this->getLocator()->utilEncoding()->service()->encodeJson($data);
    }
}
