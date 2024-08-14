<?php

namespace DZunke\FeatureFlagsBundle\Toggle;

use DZunke\FeatureFlagsBundle\Toggle\Conditions\ConditionInterface;

class ConditionBag implements \IteratorAggregate, \Countable
{

    /**
     * @var ConditionInterface[]
     */
    protected array $conditions = [];

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->conditions);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->conditions);
    }

    /**
     * @return Conditions\ConditionInterface[]
     */
    public function all(): array
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions
     * @return $this
     */
    public function add(array $conditions): self
    {
        foreach ($conditions as $condition) {
            $this->set((string)$condition, $condition);
        }

        return $this;
    }

    /**
     * @param string             $name
     * @param ConditionInterface $condition
     * @return $this
     */
    public function set(string $name, ConditionInterface $condition): self
    {
        $this->conditions[$name] = $condition;

        return $this;
    }

    /**
     * @param string $name
     * @return ConditionInterface|null
     */
    public function get(string $name): ?ConditionInterface
    {
        return $this->conditions[$name] ?? null;
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->conditions);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->conditions);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function remove(string $name): self
    {
        unset($this->conditions[$name]);

        return $this;
    }
}
