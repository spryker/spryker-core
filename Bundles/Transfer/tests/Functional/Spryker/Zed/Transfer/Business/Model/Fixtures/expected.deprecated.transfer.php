<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use Spryker\Shared\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 *
 * @deprecated Testing transfer object deprecation.
 */
class DeprecatedFooBarTransfer extends AbstractTransfer
{

    /**
     * @deprecated scalarField is deprecated.
     */
    const SCALAR_FIELD = 'scalarField';

    /**
     * @deprecated arrayField is deprecated.
     */
    const ARRAY_FIELD = 'arrayField';

    /**
     * @deprecated transferField is deprecated.
     */
    const TRANSFER_FIELD = 'transferField';

    /**
     * @deprecated transferCollectionField is deprecated.
     */
    const TRANSFER_COLLECTION_FIELD = 'transferCollectionField';

    /**
     * @var string
     */
    protected $scalarField;

    /**
     * @var array
     */
    protected $arrayField = [];

    /**
     * @var \Generated\Shared\Transfer\DeprecatedFooBarTransfer
     */
    protected $transferField;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\DeprecatedFooBarTransfer[]
     */
    protected $transferCollectionField;

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::SCALAR_FIELD => [
            'type' => 'string',
            'name_underscore' => 'scalar_field',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::ARRAY_FIELD => [
            'type' => 'array',
            'name_underscore' => 'array_field',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TRANSFER_FIELD => [
            'type' => 'Generated\Shared\Transfer\DeprecatedFooBarTransfer',
            'name_underscore' => 'transfer_field',
            'is_collection' => false,
            'is_transfer' => true,
        ],
        self::TRANSFER_COLLECTION_FIELD => [
            'type' => 'Generated\Shared\Transfer\DeprecatedFooBarTransfer',
            'name_underscore' => 'transfer_collection_field',
            'is_collection' => true,
            'is_transfer' => true,
        ],
    ];

    /**
     * @deprecated scalarField is deprecated.
     *
     * @bundle Test
     *
     * @param string $scalarField
     *
     * @return $this
     */
    public function setScalarField($scalarField)
    {
        $this->scalarField = $scalarField;
        $this->addModifiedProperty(self::SCALAR_FIELD);

        return $this;
    }

    /**
     * @deprecated scalarField is deprecated.
     *
     * @bundle Test
     *
     * @return string
     */
    public function getScalarField()
    {
        return $this->scalarField;
    }

    /**
     * @deprecated scalarField is deprecated.
     *
     * @bundle Test
     *
     * @throws \Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireScalarField()
    {
        $this->assertPropertyIsSet(self::SCALAR_FIELD);

        return $this;
    }

    /**
     * @deprecated arrayField is deprecated.
     *
     * @bundle Test
     *
     * @param array $arrayField
     *
     * @return $this
     */
    public function setArrayField(array $arrayField)
    {
        $this->arrayField = $arrayField;
        $this->addModifiedProperty(self::ARRAY_FIELD);

        return $this;
    }

    /**
     * @deprecated arrayField is deprecated.
     *
     * @bundle Test
     *
     * @return array
     */
    public function getArrayField()
    {
        return $this->arrayField;
    }

    /**
     * @deprecated arrayField is deprecated.
     *
     * @bundle Test
     *
     * @param array $arrayField
     *
     * @return $this
     */
    public function addArrayField($arrayField)
    {
        $this->arrayField[] = $arrayField;
        $this->addModifiedProperty(self::ARRAY_FIELD);

        return $this;
    }

    /**
     * @deprecated arrayField is deprecated.
     *
     * @bundle Test
     *
     * @throws \Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireArrayField()
    {
        $this->assertPropertyIsSet(self::ARRAY_FIELD);

        return $this;
    }

    /**
     * @deprecated transferField is deprecated.
     *
     * @bundle Test
     *
     * @param \Generated\Shared\Transfer\DeprecatedFooBarTransfer|null $transferField
     *
     * @return $this
     */
    public function setTransferField(DeprecatedFooBarTransfer $transferField = null)
    {
        $this->transferField = $transferField;
        $this->addModifiedProperty(self::TRANSFER_FIELD);

        return $this;
    }

    /**
     * @deprecated transferField is deprecated.
     *
     * @bundle Test
     *
     * @return \Generated\Shared\Transfer\DeprecatedFooBarTransfer
     */
    public function getTransferField()
    {
        return $this->transferField;
    }

    /**
     * @deprecated transferField is deprecated.
     *
     * @bundle Test
     *
     * @throws \Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTransferField()
    {
        $this->assertPropertyIsSet(self::TRANSFER_FIELD);

        return $this;
    }

    /**
     * @deprecated transferCollectionField is deprecated.
     *
     * @bundle Test
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\DeprecatedFooBarTransfer[] $transferCollectionField
     *
     * @return $this
     */
    public function setTransferCollectionField(\ArrayObject $transferCollectionField)
    {
        $this->transferCollectionField = $transferCollectionField;
        $this->addModifiedProperty(self::TRANSFER_COLLECTION_FIELD);

        return $this;
    }

    /**
     * @deprecated transferCollectionField is deprecated.
     *
     * @bundle Test
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DeprecatedFooBarTransfer[]
     */
    public function getTransferCollectionField()
    {
        return $this->transferCollectionField;
    }

    /**
     * @deprecated transferCollectionField is deprecated.
     *
     * @bundle Test
     *
     * @param \Generated\Shared\Transfer\DeprecatedFooBarTransfer $transferCollectionField
     *
     * @return $this
     */
    public function addTransferCollectionField(DeprecatedFooBarTransfer $transferCollectionField)
    {
        $this->transferCollectionField[] = $transferCollectionField;
        $this->addModifiedProperty(self::TRANSFER_COLLECTION_FIELD);

        return $this;
    }

    /**
     * @deprecated transferCollectionField is deprecated.
     *
     * @bundle Test
     *
     * @throws \Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTransferCollectionField()
    {
        $this->assertCollectionPropertyIsSet(self::TRANSFER_COLLECTION_FIELD);

        return $this;
    }

}
