# EdgarEzFieldTypeExtraBundle

## Installation

### Get the bundle using composer

Add EdgarEzFieldTypeExtraBundle by running this command from the terminal at the root of
your symfony project:

```bash
composer require edgar/ez-fieldtypeextra-bundle
```

## Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Edgar\EzFieldTypeExtraBundle\EdgarEzFieldTypeExtraBundle(),
        // ...
    );
}
```
