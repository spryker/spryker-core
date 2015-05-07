<?php

namespace SprykerEngine\Shared\Transfer;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Library\Filter\CamelCaseToSeparatorFilter;
use SprykerFeature\Shared\Library\Filter\FilterChain;
use SprykerFeature\Shared\Library\Filter\SeparatorToCamelCaseFilter;


abstract class AbstractTransfer implements TransferInterface, \IteratorAggregate
{

    /**
     * @var array
     */
    private static $mappingCache = [];

    /**
     * @var array
     */
    private static $mappingCache2 = [];

    /**
     * @var array
     */
    private $modifiedProperties = [];

    /**
     * @var array
     */
    private $properties = [];

    /**
     * @var \SplObjectStorage
     */
    private $objectStorage;

    /**
     * @return \SplObjectStorage
     */
    public function getIterator()
    {
        return $this->getStorage();
    }

    /**
     * @param bool $includeNullValues
     * @param bool $recursive
     * @param bool $formatToUnderscore
     *
     * @return array
     */
    public function toArray($includeNullValues = true, $recursive = true, $formatToUnderscore = true)
    {
        if ($this->getStorage()->count() > 0) {
            return $this->storageToArray();
        }
        $varsForArray = [];

        foreach (get_object_vars($this) as $name => $value) {
            // validator variables begin with an '_', so we do not want them to be copied
            if ($name[0] === '_' || $name === 'modifiedProperties' || $name === 'properties' || $name === 'objectStorage' || $name === 'locator' || $name === 'enrichAbleProperties') {
                continue;
            }

            if ($formatToUnderscore) {
                $key = $this->formatCamelCaseToUnderscore($name);
            } else {
                $key = $name;
            }

            if (is_null($value)) {
                if ($includeNullValues) {
                    $varsForArray[$key] = $value;
                }
                continue;
            }

            if (is_scalar($value)) {
                $varsForArray[$key] = $value;
                continue;
            }

            if (is_object($value)) {
                if ($recursive && $value instanceof TransferInterface) {
                    $varsForArray[$key] = $value->toArray($includeNullValues, $recursive, $formatToUnderscore);
                } else {
                    $varsForArray[$key] = $value;
                }
                continue;
            }

            if (is_array($value)) {
                $varsForArray[$key] = $value;
                continue;
            }
        }

        return $varsForArray;
    }

    /**
     * @param bool $recursive
     * @param bool $formatToUnderscore
     *
     * @return array
     */
    public function modifiedToArray($recursive = true, $formatToUnderscore = true)
    {
        $returnData = [];
        $modifiedProperties = $this->getModifiedProperties();
        foreach ($modifiedProperties as $modifiedProperty) {
            if ($formatToUnderscore) {
                $key = $this->formatCamelCaseToUnderscore($modifiedProperty);
            } else {
                $key = $modifiedProperty;
            }
            $getterName = 'get' . ucfirst($modifiedProperty);
            $value = $this->$getterName();
            if (is_object($value)) {
                if ($recursive && $value instanceof TransferInterface) {
                    $returnData[$key] = $value->modifiedToArray($recursive, $formatToUnderscore);
                } else {
                    $returnData[$key] = $value;
                }
            } else {
                $returnData[$key] = $value;
            }
        }

        return $returnData;
    }

    /**
     * @param string $property
     */
    protected function addModifiedProperty($property)
    {
        if (!in_array($property, $this->modifiedProperties)) {
            $this->modifiedProperties[] = $property;
        }
    }

    /**
     * @return array
     */
    protected function getModifiedProperties()
    {
        return $this->modifiedProperties;
    }

    /**
     * @param array $data
     * @param bool $fuzzyMatch
     *
     * @return $this
     */
    public function fromArray(array $data, $fuzzyMatch = false)
    {
        foreach ($data as $key => $value) {
            if (substr($key, -11) === '_class_name') {
                $property = lcfirst($this->formatUnderscoreToCamelCase(substr($key, 0, -11))) . '_ClassName';
                $this->$property = $value;
                continue;
            }
            $formattedKey = $this->formatUnderscoreToCamelCase($key);
            $property = lcfirst($formattedKey);
            $classNameProperty = $property . '_ClassName';
            $getter = 'get' . $formattedKey;
            $setter = 'set' . $formattedKey;

            if (method_exists($this, $getter) && $this->$getter() instanceof TransferInterface && is_array($value)) {
                $this->$getter()->fromArray($value, $fuzzyMatch);
            } elseif (is_array($value) && property_exists($this, $classNameProperty)) {
                $this->$property = $value;
            } elseif (method_exists($this, $setter)) {
                $this->$setter($value);
            } elseif (!$fuzzyMatch) {
                throw new \LogicException(
                    sprintf(
                        "Missing method or property in transfer object.\n [Transfer] %s\n[Property] %s",
                        get_class($this),
                        $property
                    )
                );
            }
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function validate()
    {
        $data = get_object_vars($this);
        foreach ($data as $key => $value) {
            // validator variables begin with an '_', so we do not want them to be copied
            if ($key[0] === '_' || $key === 'modifiedProperties' || $key === 'locator' || $key === 'enrichAbleProperties') {
                continue;
            }
            // hack end
            $rules = $this->getRule($key);
            foreach ($rules as $rule) {
                if (is_null($value)) {
                    continue;
                }

                $result = call_user_func($rule, $value);
                if (false === $result) {
                    throw new \Exception("Validator $rule failed for '$key'");
                }
            }
        }
    }

    /**
     * @param $key
     *
     * @return array
     */
    private function getRule($key)
    {
        $key = '_' . $key;
        if (isset($this->$key)) {
            return $this->$key;
        } else {
            return [];
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function formatUnderscoreToCamelCase($value)
    {
        if (!isset(self::$mappingCache[$value])) {
            $filterChain = new FilterChain();
            $filterChain
                ->addFilter(new SeparatorToCamelCaseFilter('_', true))
                ->addFilter(new SeparatorToCamelCaseFilter('-', true))
            ;

            self::$mappingCache[$value] = $filterChain->filter($value);
        }

        return self::$mappingCache[$value];
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function formatCamelCaseToUnderscore($value)
    {
        if (!isset(self::$mappingCache2[$value])) {
            $filter = new CamelCaseToSeparatorFilter('_');
            self::$mappingCache2[$value] = $filter->filter($value);
        }

        return self::$mappingCache2[$value];
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function fillWithFixtureData(array $data = [])
    {
        $this->fromArray($data);

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return (empty($this->modifiedProperties));
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return array_keys($this->toArray(false, false, false));
    }

    public function __clone()
    {
        foreach (get_object_vars($this) as $key => $value) {
            if (is_object($value)) {
                if ($value instanceof LocatorLocatorInterface) {
                    $this->$key = $value;
                } else {
                    $this->$key = clone $value;
                }
            }
        }
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return null;
    }

    /**
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->properties)) {
            return $this->properties[$key];
        }

        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->properties[$key] = $value;
    }

    /**
     * @param $method
     * @param $args
     */
    public function __call($method, $args)
    {
    }

    /**
     * @return \SplObjectStorage
     */
    private function getStorage()
    {
        if (is_null($this->objectStorage)) {
            $this->objectStorage = new \SplObjectStorage();
        }

        return $this->objectStorage;
    }

    /**
     * @return array
     */
    private function storageToArray()
    {
        $storage = $this->getStorage();
        $data = [];
        foreach ($storage as $object) {
            $data[] = $object->toArray();
        }

        return $data;
    }

    /**
     * @param $object
     *
     * @return $this
     */
    public function add($object)
    {
        $storage = $this->getStorage();
        $storage->attach($object);

        return $this;
    }

    /**
     * @return object
     */
    public function getFirstItem()
    {
        $this->getStorage()->rewind();

        return $this->getStorage()->current();
    }
}
