<?php

namespace PeteMc\FeatureGate;

class FeatureGate
{
    /**
     * @var Feature[]
     */
    private $features = [];

    public function createFeature($featureName, \Closure $activeCheck = null)
    {
        $this->features[$featureName] = new Feature($activeCheck);
    }

    public function activate(string $featureName)
    {
        if (!array_key_exists($featureName, $this->features)) {
            $this->features[$featureName] = new Feature;
        }

        $this->features[$featureName]->activate();
    }

    public function deactivate(string $featureName)
    {
        if (!array_key_exists($featureName, $this->features)) {
            $this->features[$featureName] = new Feature;
        }

        $this->features[$featureName]->deactivate();
    }

    public function isActive(string $feature): bool
    {
        return array_key_exists($feature, $this->features) && $this->features[$feature]->isActive();
    }

    public function activateFeatureForId(string $featureName, $id)
    {
        if (!array_key_exists($featureName, $this->features)) {
            $this->features[$featureName] = new Feature;
        }
        $this->features[$featureName]->activateForId($id);
    }

    public function deactivateFeatureForId(string $featureName, $id)
    {
        if (!array_key_exists($featureName, $this->features)) {
            $this->features[$featureName] = new Feature;
        }
        $this->features[$featureName]->deactivateForId($id);
    }

    public function isActiveForId(string $featureName, $id): Bool
    {
        if (array_key_exists($featureName, $this->features)) {
            return $this->features[$featureName]->isActiveForId($id);
        }
    }
}
