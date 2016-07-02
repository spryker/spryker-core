<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGenerator;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeManager implements AttributeManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGenerator
     */
    protected $transferGenerator;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToLocaleInterface $localeFacade
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGenerator
     */
    protected function getTransferGenerator()
    {
        if ($this->transferGenerator === null) {
            $this->transferGenerator = new ProductAttributeTransferGenerator();
        }

        return $this->transferGenerator;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->innerJoinSpyProductManagementAttributeMetadata()
            ->innerJoinSpyProductManagementAttributeInput()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeLocalizedTransfer[]
     */
    public function getProductAttributeLocalizedCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeLocalized()
            ->innerJoinSpyLocale()
            ->innerJoinSpyProductManagementAttribute()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeLocalizedCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeMetadataTransfer[]
     */
    public function getProductAttributeMetadataCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeMetadata()
            ->innerJoinSpyProductManagementAttributeType()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeMetadataCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeInputTransfer[]
     */
    public function getProductAttributesInputCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeInput()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeInputCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTypeTransfer[]
     */
    public function getProductAttributesTypeCollection()
    {
        die('fix me');
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeType()
            ->find();

        foreach ($collection as $typeTransfer) {
            sd($typeTransfer->toArray());
            $inputCollection = $this->productManagementQueryContainer
                ->queryProductManagementAttributeInput()
                ->filterByIdProductManagementAttributeInput($metadataTransfer->getType()->getIdProductManagementAttributeIn())
                ->find();
            //$inputTransfer = $this->convertProductAttributeInput($typeEntity->getType());
            //$typeTransfer->setInput($inputTransfer);
        }

        return $this->getTransferGenerator()->convertProductAttributeTypeCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function getProductAttributesValueCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeValue()
            ->find();

        return $this->getTransferGenerator()->convertProductAttributeValueCollection($collection);
    }

    protected function loadInput()
    {
        $inputCollection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeMetadata()
            ->filterByFkType($metadataTransfer->getType()->getIdProductManagementAttributeIn())
            ->find();

    }

}
