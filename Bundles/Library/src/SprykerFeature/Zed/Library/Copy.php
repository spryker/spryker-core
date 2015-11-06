<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library;

use SprykerEngine\Shared\Transfer\AbstractTransfer;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;

class Copy
{

    /**
     * TODO arguments must be switched entityToTransfer i expect entity to be the first argument
     *
     * @param AbstractTransfer $transfer
     * @param ActiveRecordInterface $entity
     * @param bool $enrich
     *
     * @return AbstractTransfer
     */
    public static function entityToTransfer(AbstractTransfer $transfer, ActiveRecordInterface $entity, $enrich = false)
    {
        $enrichAbleProperties = self::getEnrichAbleProperties($transfer, $enrich);
        $methods = get_class_methods($entity);
        foreach ($methods as $method) {
            if (strpos($method, 'get') === 0) {
                $property = str_replace('get', '', $method);
                $enrichAbleProperty = lcfirst($property);
                $setMethod = 'set' . $property;
                if (method_exists($transfer, $setMethod)) {
                    $value = $entity->$method();
                    if ($enrichAbleProperties && array_key_exists($enrichAbleProperty, $enrichAbleProperties)) {
                        $enrichTransfer = new $enrichAbleProperties[$enrichAbleProperty]();
                        if ($value instanceof ActiveRecordInterface) {
                            $enrichedTransfer = self::entityToTransfer($enrichTransfer, $value, $enrich);
                            $transfer->$setMethod($enrichedTransfer);
                        } elseif ($value instanceof \Propel\Runtime\Collection\Collection) {
                            $enrichedTransferCollection = self::entityCollectionToTransferCollection($enrichTransfer, $value, $enrich);
                            $transfer->$setMethod($enrichedTransferCollection);
                        }
                    }

                    // just in case no other previous rule matched
                    if (is_object($value)) {
                        continue;
                    }

                    if ($value !== null) {
                        $transfer->$setMethod($value);
                    }
                }
            }
        }

        return $transfer;
    }

    /**
     * @param AbstractTransfer $transfer
     * @param ActiveRecordInterface $entity
     * @param bool $enrich
     *
     * @return ActiveRecordInterface
     */
    public static function transferToEntity(AbstractTransfer $transfer, ActiveRecordInterface $entity, $enrich = false)
    {
        $enrichAbleProperties = self::getEnrichAbleProperties($transfer, $enrich);
//        $enrichAbleEntityLoaderMethods = self::getEnrichAbleEntityLoaderMethods($transfer, $enrich);
        $methods = get_class_methods($transfer);
        foreach ($methods as $method) {
            if (strpos($method, 'get') === 0) {
                $property = substr($method, 3);
                $enrichAbleProperty = lcfirst($property);
                $setMethod = 'set' . $property;

                if (method_exists($entity, $setMethod)) {
                    $value = $transfer->$method();

//                    if ($enrichAbleProperties && array_key_exists($enrichAbleProperty, $enrichAbleProperties)) {
//                        $enrichTransfer = new $enrichAbleProperties[$enrichAbleProperty];
//                        if ($value instanceof AbstractTransfer) {
//                            $entityToEnrich = call_user_func(
//                                ['Generated_Zed_EntityLoader', $enrichAbleEntityLoaderMethods[$enrichAbleProperty]]
//                            );
//                            $enrichedEntity = self::transferToEntity($enrichTransfer, $entityToEnrich, $enrich);
//                            $entity->$setMethod($enrichedEntity);
//                        } elseif ($value instanceof AbstractTransferCollection) {
//                            $entityCollection = $entity->$method();
//                            $enrichedEntityCollection = self::transferCollectionToEntityCollection($value, $entityCollection, $enrich);
//                            $entity->$setMethod($enrichedEntityCollection);
//                        }
//                    }

                    if (is_object($value)) {
                        continue;
                    }

                    /**
                     * @todo think about it
                     * Propel performs an (int) cast on each value which is passed to an INT DB field.
                     * If we do not have an autoincrement value set in the transfer object e.g. $value = '' (we want to
                     * create a new entry), this value would be casted to 0. Usually the primary key of a table is an
                     * INT auto_increment field and so this value cannot be inserted at least in mysql strict mode.
                     *
                     * Only set values which are not NULL and which are not an empty string
                     */
                    if ($value !== null && $value !== '') {
                        $entity->$setMethod($value);
                    }
                }
            }
        }

        return $entity;
    }

    /**
     * Copies all values from a transfer object to the entity object
     * where the transfer value is NOT NULL!
     *
     *  WARNING: Be carefull using this method and think if this is
     *  what you need!
     *
     * @param AbstractTransfer $transfer
     * @param ActiveRecordInterface $entity
     *
     * @return ActiveRecordInterface
     */
    public static function transferToEntityNoNullValues(AbstractTransfer $transfer, ActiveRecordInterface $entity)
    {
        $methods = get_class_methods($transfer);
        foreach ($methods as $method) {
            if (strpos($method, 'get') === 0) {
                $setMethod = str_replace('get', 'set', $method);
                if (method_exists($entity, $setMethod) && null !== $transfer->$method()) {
                    $entity->$setMethod($transfer->$method());
                }
            }
        }

        return $entity;
    }

    /**
     * @param $transferCollection
     * @param Collection $entityCollection
     * @param $enrich
     *
     * @return mixed
     */
    public static function entityCollectionToTransferCollection($transferCollection, Collection $entityCollection, $enrich)
    {
        foreach ($entityCollection as $entity) {
            $transfer = $transferCollection->getEmptyTransferItem();
            $transfer = self::entityToTransfer($transfer, $entity, $enrich);
            $transferCollection->add($transfer);
        }

        return $transferCollection;
    }

    /**
     * @param $transferCollection
     * @param Collection $entityCollection
     * @param $enrich
     *
     * @return Collection
     */
    private static function transferCollectionToEntityCollection($transferCollection, Collection $entityCollection, $enrich)
    {
        foreach ($transferCollection as $transfer) {
            $entityName = $entityCollection->getModel();
            $entity = new $entityName();
            $entity = self::transferToEntity($transfer, $entity, $enrich);
            $entityCollection->append($entity);
        }

        return $entityCollection;
    }

    /**
     * @param AbstractTransfer $transfer
     * @param $enrich
     *
     * @return array
     */
    private static function getEnrichAbleProperties(AbstractTransfer $transfer, $enrich)
    {
        $enrichAbleProperties = [];
        if ($enrich) {
            if (method_exists($transfer, 'getEnrichAbleProperties')) {
                $enrichAbleProperties = $transfer->getEnrichAbleProperties();
            }
            if (is_array($enrich)) {
                $callback = function ($key) use ($enrich) {
                    return in_array($key, $enrich);
                };
                $enrichAbleProperties = array_flip(
                    array_filter(
                        array_flip($enrichAbleProperties),
                        $callback
                    )
                );
            }
        }

        return $enrichAbleProperties;
    }

    /**
     * @param AbstractTransfer $transfer
     * @param $enrich
     *
     * @return array
     */
    private static function getEnrichAbleEntityLoaderMethods(AbstractTransfer $transfer, $enrich)
    {
        $enrichAbleEntityLoaderMethods = [];
        if ($enrich) {
            if (method_exists($transfer, 'getEnrichAbleEntityLoaderMethods')) {
                $enrichAbleEntityLoaderMethods = $transfer->getEnrichAbleEntityLoaderMethods();
            }
            if (is_array($enrich)) {
                $callback = function ($key) use ($enrich) {
                    return in_array($key, $enrich);
                };
                $enrichAbleEntityLoaderMethods = array_flip(
                    array_filter(
                        array_flip($enrichAbleEntityLoaderMethods),
                        $callback
                    )
                );
            }
        }

        return $enrichAbleEntityLoaderMethods;
    }

}
