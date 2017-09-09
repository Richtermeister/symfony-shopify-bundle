<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api;

class GenericResource implements \ArrayAccess
{

    /**
     * @var array
     */
    private $data = array();

    /**
     * @param array $data
     */
    public function hydrate(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function get($param, $default = null)
    {
        return $this->offsetExists($param) ? $this->offsetGet($param) : $default;
    }

    /**
     * @param array $data
     * @return GenericResource
     */
    public static function create(array $data = array())
    {
        $entity = new static();
        $entity->hydrate($data);
        return $entity;
    }
}
