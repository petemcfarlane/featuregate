# A simple Feature Gate

The simplest way to use the feature gate is to globally activate, or deactivate, a single feature

```php
// configure the feature gate
$gate = new FeatureGate;

$gate->activate('special-sauce');

// use the gate
if ($gate->isActive('special-sauce') {
    // then do something cool
}
```

Gates are disabled by false, if not configured.
```php
$gate->isActive('not-yet-configured');
// false
```

Gates can also be enabled for one or more IDs
```php
$gate->activateFeatureForId('new-feature', 123);

$gate->isActiveForId('new-feature', 123));
// true

$gate->isActiveForId('new-feature', 456));
// false
```

But the coolest thing about this feature gate is you can supply your own Closure at configuration to determine if a feature should be active for a given ID or not:
```php
$gate->createFeature('is-dessert', function ($id, $enabled) {
    return in_array($id, ['ice cream', 'chocolate mousse', 'sticky toffee']);
});

$gate->isActiveFor('is-dessert', 'cheese');
// false

$gate->isActiveFor('is-dessert', 'sticky toffee');
// true
```

## Configuration

Ideally in some service provider:
```
$gate = new FeatureGate;
$gate->activate('feature-1');
$gate->deactivate('feature-2');
$gate->activate('feature-3');

$gate->activateFeatureForId('feature-3', 27);
```

## Persistence
Hopefully it's not hard to see how the above feature gate could be stored in a relational database or configuration file if needed, though I've not tested this yet. Might need to try [jeremeamia/superclosure](https://github.com/jeremeamia/super_closure) to serialise the closures if they are being used.

|feature_name|active|for |active_check|
|------------|------|----|------------|
|feature-1   |true  |    |            |
|feature-2   |false |    |            |
|feature-3   |true  |[27]|            |

