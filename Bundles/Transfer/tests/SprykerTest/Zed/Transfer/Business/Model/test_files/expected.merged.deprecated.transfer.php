<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 *
 * @deprecated Testing transfer object deprecation.
 */
class MergedDeprecatedFooBarTransfer extends AbstractTransfer
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
     * @deprecated Deprecated on project level.
     */
    const PROJECT_LEVEL_DEPRECATED_FIELD = 'projectLevelDeprecatedField';

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
     * @var string
     */
    protected $projectLevelDeprecatedField;

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
        self::PROJECT_LEVEL_DEPRECATED_FIELD => [
            'type' => 'string',
            'name_underscore' => 'project_level_deprecated_field',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];

    /**
     * @module Deprecated
     *
     * @deprecated scalarField is deprecated.
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
     * @module Deprecated
     *
     * @deprecated scalarField is deprecated.
     *
     * @return string
     */
    public function getScalarField()
    {
        return $this->scalarField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated scalarField is deprecated.
     *
     * @return $this
     */
    public function requireScalarField()
    {
        $this->assertPropertyIsSet(self::SCALAR_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated arrayField is deprecated.
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
     * @module Deprecated
     *
     * @deprecated arrayField is deprecated.
     *
     * @return array
     */
    public function getArrayField()
    {
        return $this->arrayField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated arrayField is deprecated.
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
     * @module Deprecated
     *
     * @deprecated arrayField is deprecated.
     *
     * @return $this
     */
    public function requireArrayField()
    {
        $this->assertPropertyIsSet(self::ARRAY_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferField is deprecated.
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
     * @module Deprecated
     *
     * @deprecated transferField is deprecated.
     *
     * @return \Generated\Shared\Transfer\DeprecatedFooBarTransfer
     */
    public function getTransferField()
    {
        return $this->transferField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferField is deprecated.
     *
     * @return $this
     */
    public function requireTransferField()
    {
        $this->assertPropertyIsSet(self::TRANSFER_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferCollectionField is deprecated.
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\DeprecatedFooBarTransfer[] $transferCollectionField
     *
     * @return $this
     */
    public function setTransferCollectionField(ArrayObject $transferCollectionField)
    {
        $this->transferCollectionField = $transferCollectionField;
        $this->addModifiedProperty(self::TRANSFER_COLLECTION_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferCollectionField is deprecated.
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DeprecatedFooBarTransfer[]
     */
    public function getTransferCollectionField()
    {
        return $this->transferCollectionField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated transferCollectionField is deprecated.
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
     * @module Deprecated
     *
     * @deprecated transferCollectionField is deprecated.
     *
     * @return $this
     */
    public function requireTransferCollectionField()
    {
        $this->assertCollectionPropertyIsSet(self::TRANSFER_COLLECTION_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated Deprecated on project level.
     *
     * @param string $projectLevelDeprecatedField
     *
     * @return $this
     */
    public function setProjectLevelDeprecatedField($projectLevelDeprecatedField)
    {
        $this->projectLevelDeprecatedField = $projectLevelDeprecatedField;
        $this->addModifiedProperty(self::PROJECT_LEVEL_DEPRECATED_FIELD);

        return $this;
    }

    /**
     * @module Deprecated
     *
     * @deprecated Deprecated on project level.
     *
     * @return string
     */
    public function getProjectLevelDeprecatedField()
    {
        return $this->projectLevelDeprecatedField;
    }

    /**
     * @module Deprecated
     *
     * @deprecated Deprecated on project level.
     *
     * @return $this
     */
    public function requireProjectLevelDeprecatedField()
    {
        $this->assertPropertyIsSet(self::PROJECT_LEVEL_DEPRECATED_FIELD);

        return $this;
    }

}
