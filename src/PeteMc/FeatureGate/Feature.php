<?php

namespace PeteMc\FeatureGate;

class Feature
{
    /**
     * @var bool
     */
    private $active = false;

    /**
     * @var array
     */
    private $for = [];

    /**
     * @var \Closure
     */
    private $activeCheck;

    public function __construct(\Closure $activeCheck = null)
    {
        $this->activeCheck = $activeCheck;
    }

    public function activate()
    {
        $this->active = true;
    }

    public function deactivate()
    {
        $this->active = false;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function activateForId($id)
    {
        if (!in_array($id, $this->for)) {
            array_push($this->for, $id);
        }
    }

    public function deactivateForId($id)
    {
        if (in_array($id, $this->for)) {
            $this->for = array_filter($this->for, function ($activeId) use ($id) {
                return $activeId != $id;
            });
        }
    }

    public function isActiveForId($id): bool
    {
        return is_null($this->activeCheck) ? in_array($id, $this->for) : ($this->activeCheck)($id, $this->for);
    }
}
