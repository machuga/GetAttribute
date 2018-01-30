<?php

trait GetAttribute
{
    protected $keyExists = false;

    public function getRelationValue($key)
    {
        if ($this->relationLoaded($key)) {
            $this->keyExists = true;
            return $this->relations[$key];
        }

        if (method_exists($this, $key)) {
            $this->keyExists = true;
            return $this->getRelationshipFromMethod($key);
        }
    }

    public function getAttribute($key)
    {
        if (! $key) {
            return;
        }

        if (array_key_exists($key, $this->attributes) ||
            $this->hasGetMutator($key)) {
            $this->keyExists = true;
            return $this->getAttributeValue($key);
        }

        if (method_exists(self::class, $key)) {
            $this->keyExists = true;
            return;
        }

        return $this->getRelationValue($key);
    }

    public function __get($key)
    {
        $this->keyExists = false;
        $result = parent::__get($key);

        if ($this->keyExists === false)
            throw new \ErrorException("Undefined property $key");

        return $result;
    }
}