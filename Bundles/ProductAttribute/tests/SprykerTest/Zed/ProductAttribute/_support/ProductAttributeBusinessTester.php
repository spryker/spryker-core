<?php
namespace SprykerTest\Zed\ProductAttribute;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface;

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
class ProductAttributeBusinessTester extends Actor
{

    use _generated\ProductAttributeBusinessTesterActions;

    const ABSTRACT_SKU = 'testFooBarAbstract';
    const CONCRETE_SKU = 'testFooBarConcrete';
    const SUPER_ATTRIBUTE_KEY = 'super_attribute';
    const SUPER_ATTRIBUTE_VALUE = 'very super attribute';
    const FOO_ATTRIBUTE_KEY = 'foo';

    const DATA_PRODUCT_ATTRIBUTES_VALUES = [
        'foo' => 'Foo Value',
        'bar' => '20 units',
    ];

    const DATA_PRODUCT_LOCALIZED_ATTRIBUTES_VALUES = [
        46 => [
            'foo' => 'Foo Value DE',
        ],
        66 => [
            'foo' => 'Foo Value US',
        ],
    ];
    const PRODUCT_ATTRIBUTE_VALUES = [
        '_' => [
            'foo' => 'Foo Value',
            'bar' => '20 units',
        ],
        46 => [
            'foo' => 'Foo Value DE',
        ],
        66 => [
            'foo' => 'Foo Value US',
        ],
    ];

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface $productFacade
     *
     * @return void
     */
    public function setProductFacade(ProductAttributeToProductInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     *
     * @return void
     */
    public function setProductAttributeFacade(ProductAttributeFacadeInterface $productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param array $values
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute
     */
    public function createProductManagementAttributeEntity(array $values = [])
    {
        $productAttributeKeyEntity = new SpyProductAttributeKey();
        $productAttributeKeyEntity->setKey('some_unique_key_that_should_not_exist_in_db');
        $productAttributeKeyEntity->save();

        $productManagementAttributeEntity = new SpyProductManagementAttribute();
        $productManagementAttributeEntity
            ->setFkProductAttributeKey($productAttributeKeyEntity->getIdProductAttributeKey())
            ->setInputType('bar');
        $productManagementAttributeEntity->save();

        if (!empty($values)) {
            foreach ($values as $value) {
                $productManagementAttributeValueEntity = new SpyProductManagementAttributeValue();
                $productManagementAttributeValueEntity
                    ->setFkProductManagementAttribute($productManagementAttributeEntity->getIdProductManagementAttribute())
                    ->setValue($value);
                $productManagementAttributeValueEntity->save();
            }
        }

        return $productManagementAttributeEntity;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName)
    {
        $localeEntity = SpyLocaleQuery::create()
            ->filterByLocaleName($localeName)
            ->findOneOrCreate();

        $localeEntity->save();

        $localeTransfer = (new LocaleTransfer())->fromArray($localeEntity->toArray(), true);

        return $localeTransfer;
    }

    /**
     * @return array
     */
    public function generateLocalizedAttributes()
    {
        $results = [];
        foreach (static::DATA_PRODUCT_LOCALIZED_ATTRIBUTES_VALUES as $idLocale => $localizedData) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setIdLocale($idLocale);

            $localizedAttributeTransfer = new LocalizedAttributesTransfer();
            $localizedAttributeTransfer->setAttributes($localizedData);
            $localizedAttributeTransfer->setLocale($localeTransfer);
            $localizedAttributeTransfer->setName('product-' . rand(1, 1000));

            $results[] = $localizedAttributeTransfer;
        }

        return $results;
    }

    /**
     * @param string $sku
     * @param null|array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function createSampleAbstractProduct($sku, $data = null)
    {
        $data = (!is_array($data)) ? ProductAttributeBusinessTester::DATA_PRODUCT_ATTRIBUTES_VALUES : $data;

        $productAbstractTransfer = $this->haveProductAbstract([
            'attributes' => $data,
            'sku' => $sku,
        ]);

        $localizedAttributes = $this->generateLocalizedAttributes();
        $productAbstractTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributes));

        $idProductAbstract = $this->productFacade->saveProduct($productAbstractTransfer, []);
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param string $sku
     * @param null|array $data
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createSampleProduct(ProductAbstractTransfer $productAbstractTransfer, $sku, $data = null)
    {
        $data = (!is_array($data)) ? ProductAttributeBusinessTester::DATA_PRODUCT_ATTRIBUTES_VALUES : $data;

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setSku($sku);
        $productConcreteTransfer->setAttributes($data);

        $localizedAttributes = $this->generateLocalizedAttributes();
        $productConcreteTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributes));

        $this->productFacade->saveProduct($productAbstractTransfer, [$productConcreteTransfer]);

        return $productConcreteTransfer;
    }

    /**
     * @param string $key
     * @param bool $isSuper
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createSampleAttributeMetadata($key, $isSuper = false)
    {
        $productManagementAttributeTransfer = (new ProductManagementAttributeTransfer())
            ->setIsSuper($isSuper)
            ->setKey($key)
            ->setInputType('text');

        $this->productAttributeFacade->createProductManagementAttribute($productManagementAttributeTransfer);

        return $productManagementAttributeTransfer;
    }

    /**
     * @return array
     */
    public function createSampleAttributeMetadataWithSuperAttributeData()
    {
        $this->createSampleAttributeMetadata(ProductAttributeBusinessTester::FOO_ATTRIBUTE_KEY, false);
        $this->createSampleAttributeMetadata(ProductAttributeBusinessTester::SUPER_ATTRIBUTE_KEY, true);

        $data = ProductAttributeBusinessTester::DATA_PRODUCT_ATTRIBUTES_VALUES;
        $data[ProductAttributeBusinessTester::SUPER_ATTRIBUTE_KEY] = ProductAttributeBusinessTester::SUPER_ATTRIBUTE_VALUE;

        return $data;
    }

}
