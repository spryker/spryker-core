<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\PriceType;

use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Zed\PriceProduct\Business\Exception\UnknownPriceProductTypeException;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class PriceProductTypeReader implements PriceProductTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface
     */
    protected $productPriceTypeMapper;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $priceProductConfig;

    /**
     * @var \Orm\Zed\PriceProduct\Persistence\SpyPriceType[]
     */
    protected static $priceTypeCache = [];

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\ProductPriceTypeMapperInterface $productPriceTypeMapper
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceProductConfig
     */
    public function __construct(
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        ProductPriceTypeMapperInterface $productPriceTypeMapper,
        PriceProductConfig $priceProductConfig
    ) {
        $this->queryContainer = $priceProductQueryContainer;
        $this->productPriceTypeMapper = $productPriceTypeMapper;
        $this->priceProductConfig = $priceProductConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
     */
    public function getPriceTypes()
    {
        $priceTypeEntities = $this->queryContainer
            ->queryAllPriceTypes()
            ->find();

        $priceTypes = [];
        foreach ($priceTypeEntities as $priceTypeEntity) {
            $priceTypes[] = $this->productPriceTypeMapper->mapFromEntity($priceTypeEntity);
        }

        return $priceTypes;
    }

    /**
     * @param string $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer|null
     */
    public function findPriceTypeByName(string $priceTypeName): ?PriceTypeTransfer
    {
        if ($this->hasPriceType($priceTypeName) === false) {
            return null;
        }

        return $this->productPriceTypeMapper->mapFromEntity(static::$priceTypeCache[$priceTypeName]);
    }

    /**
     * @param string|null $priceType
     *
     * @return string
     */
    public function handleDefaultPriceType($priceType = null)
    {
        if ($priceType === null) {
            $priceType = $this->priceProductConfig->getPriceTypeDefaultName();
        }

        return $priceType;
    }

    /**
     * @param string $priceTypeName
     *
     * @throws \Spryker\Zed\PriceProduct\Business\Exception\UnknownPriceProductTypeException
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceType
     */
    public function getPriceTypeByName($priceTypeName)
    {
        if (!$this->hasPriceType($priceTypeName)) {
            throw new UnknownPriceProductTypeException(
                sprintf('Unknown price type "%s" given.', $priceTypeName)
            );
        }

        return static::$priceTypeCache[$priceTypeName];
    }

    /**
     * @param string $priceTypeName
     *
     * @return bool
     */
    public function hasPriceType($priceTypeName)
    {
        if (isset(static::$priceTypeCache[$priceTypeName])) {
            return true;
        }

        $priceTypeEntity = $this->queryContainer
            ->queryPriceType($priceTypeName)
            ->findOne();

        if ($priceTypeEntity === null) {
            return false;
        }

        static::$priceTypeCache[$priceTypeName] = $priceTypeEntity;

        return true;
    }
}
