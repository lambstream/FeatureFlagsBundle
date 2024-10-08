<?php

namespace DZunke\FeatureFlagsBundle;

class Context
{

    /**
     * @var array
     */
    private $context = [];

    /**
     * @param string $name
     * @param mixed  $value
     * @return $this
     */
    public function set($name, $value): self
    {
        $this->context[(string)$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return null
     */
    public function get($name)
    {
        return isset($this->context[(string)$name]) ? $this->context[(string)$name] : null;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->context;
    }

    /**
     * @return $this
     */
    public function clear(): self
    {
        $this->context = [];

        return $this;
    }

}
