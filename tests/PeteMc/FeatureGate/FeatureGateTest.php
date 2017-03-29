<?php

namespace PeteMc\FeatureGate;

use PHPUnit\Framework\TestCase;

class FeatureGateTest extends TestCase
{
    /**
     * @test
     */
    function a_feature_can_be_globally_activated()
    {
        $featureGate = new FeatureGate;

        $featureGate->activate('new-feature');

        $this->assertTrue($featureGate->isActive('new-feature'));
    }

    /**
     * @test
     */
    function a_feature_can_be_globally_deactivated()
    {
        $featureGate = new FeatureGate;

        $featureGate->deactivate('new-feature');

        $this->assertFalse($featureGate->isActive('new-feature'));
    }

    /**
     * @test
     */
    function a_feature_is_deactivated_by_default_if_it_has_not_been_configured()
    {
        $featureGate = new FeatureGate;

        $this->assertFalse($featureGate->isActive('new-feature'));
    }

    /**
     * @test
     */
    function a_feature_can_be_activated_for_some_id()
    {
        $id = 1;
        $id2 = 2;
        $featureGate = new FeatureGate;

        $featureGate->activateFeatureForId('new-feature', $id);
        $featureGate->activateFeatureForId('new-feature', $id2);

        $this->assertTrue($featureGate->isActiveForId('new-feature', $id));
        $this->assertTrue($featureGate->isActiveForId('new-feature', $id2));
    }

    /**
     * @test
     */
    function a_feature_can_be_deactivated_for_some_id()
    {
        $id = 3;
        $featureGate = new FeatureGate;

        $featureGate->activateFeatureForId('new-feature', $id);
        $this->assertTrue($featureGate->isActiveForId('new-feature', $id));

        $featureGate->deactivateFeatureForId('new-feature', $id);
        $this->assertFalse($featureGate->isActiveForId('new-feature', $id));
    }

    /**
     * @test
     */
    function a_feature_can_be_active_for_some_id_but_globally_deactivated()
    {
        $id = 4;
        $featureGate = new FeatureGate;

        $featureGate->activateFeatureForId('new-feature', $id);

        $this->assertTrue($featureGate->isActiveForId('new-feature', $id));
        $this->assertFalse($featureGate->isActive('new-feature'));
    }

    /**
     * @test
     */
    function a_custom_callback_can_be_added_to_determine_if_an_id_is_active()
    {
        $enableEvenIds = function ($id, $enabled) {
            return $id % 2 == 0;
        };

        $featureGate = new FeatureGate;

        $featureGate->createFeature('enable-even-ids', $enableEvenIds);

        $this->assertFalse($featureGate->isActiveForId('enable-even-ids', 1));
        $this->assertTrue($featureGate->isActiveForId('enable-even-ids', 2));
        $this->assertFalse($featureGate->isActiveForId('enable-even-ids', 3));
        $this->assertTrue($featureGate->isActiveForId('enable-even-ids', 4));
    }

    /**
     * @test
     */
    function test_a_gate_using_a_closure_with_a_dependency()
    {
        $someDependency = new class
        {
            public function findSomething($id)
            {
                return $id == 42;
            }
        };

        $checkWithDependency = function ($id, $enabled) use ($someDependency) {
            return $someDependency->findSomething($id);
        };

        $featureGate = new FeatureGate;

        $featureGate->createFeature('check-with-dependency', $checkWithDependency);

        $this->assertTrue($featureGate->isActiveForId('check-with-dependency', 42));
        $this->assertFalse($featureGate->isActiveForId('check-with-dependency', 99));
        $this->assertFalse($featureGate->isActiveForId('check-with-dependency', 123));

        $this->assertFalse($featureGate->isActive('check-with-dependency'));
    }
}
