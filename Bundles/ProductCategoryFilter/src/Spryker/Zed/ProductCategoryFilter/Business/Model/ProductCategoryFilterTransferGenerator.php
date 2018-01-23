<?php
/**
 * Created by PhpStorm.
 * User: ahmedsabaa
 * Date: 1/23/18
 * Time: 3:52 PM
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;


use Generated\Shared\Transfer\ProductCategoryFilterItemTransfer;
use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilter\Dependency\Service\ProductCategoryFilterToUtilEncodingServiceInterface;

class ProductCategoryFilterTransferGenerator implements ProductCategoryFilterTransferGeneratorInterface
{
    const IS_ACTIVE_FIELD = 'isActive';
    const LABEL_FIELD = 'label';

    /**
     * @var \Spryker\Zed\ProductCategoryFilterGui\Dependency\Service\ProductCategoryFilterGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Dependency\Service\ProductCategoryFilterToUtilEncodingServiceInterface
     */
    public function __construct(ProductCategoryFilterToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int $idProductCategoryFilter
     * @param int $idCategory
     * @param string $jsonData
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferFromJson($idProductCategoryFilter, $idCategory, $jsonData)
    {
        $productCategoryFilterTransfer = new ProductCategoryFilterTransfer();
        $productCategoryFilterTransfer->setIdProductCategoryFilter($idProductCategoryFilter);
        $productCategoryFilterTransfer->setFkCategory($idCategory);

        if(empty($jsonData)) {
            return $productCategoryFilterTransfer;
        }

        $data = call_user_func_array(
            'array_merge',
            $this->utilEncodingService->decodeJson($jsonData,true)
        );

        foreach ($data as $key => $value) {
            $productCategoryFilterItemTransfer = new ProductCategoryFilterItemTransfer();
            $productCategoryFilterItemTransfer->setIsActive($value[static::IS_ACTIVE_FIELD]);
            $productCategoryFilterItemTransfer->setLabel($value[static::LABEL_FIELD]);
            $productCategoryFilterItemTransfer->setKey($key);

            $productCategoryFilterTransfer->addProductCategoryFilterItem($productCategoryFilterItemTransfer);
        }

        return $productCategoryFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function generateTransferWithJsonFromTransfer(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        $finalJson = [];

        $productCategoryFilterItemTransfers = $productCategoryFilterTransfer->getFilters();
        foreach($productCategoryFilterItemTransfers as $productCategoryFilterItemTransfer) {
            $finalJson[] = [
                $productCategoryFilterItemTransfer->getKey() => [
                    static::IS_ACTIVE_FIELD => $productCategoryFilterItemTransfer->getIsActive(),
                    static::LABEL_FIELD => $productCategoryFilterItemTransfer->getLabel(),
                    ]
            ];
        }

        $productCategoryFilterTransfer->setFilterData($this->utilEncodingService->encodeJson($finalJson,true));

        return $productCategoryFilterTransfer;
    }
}