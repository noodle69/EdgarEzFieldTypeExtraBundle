# EdgarEzFieldTypeExtraBundle

## Command

example :

```
php bin/console edgar:generate:fieldtype --namespace=Acme/BarBundle --fieldtype-name=Bar --fieldtype-namespace=acme
```

After generating new FieldType Bundle, a notification inform to modify your composer.json to add new bundle to composer autoload.

So, edit your composer.json and add following lines to "autoload" "psr-4" sectioon:

```json
{
    "name": "ezsystems/ezplatform",
    ...
    "autoload": {
        "psr-4": {
            "AppBundle\\": "src/AppBundle/",
            ...
            "<Vendor>\\<FieldTypeBundle>\\": "src/<Vendor>/<FieldTypeBundle>/src/bundle/",
            "<Vendor>\\<FieldType>\\": "src/<Vendor>/<FieldTypeBundle>/src/lib/",
            ...
        },
        "classmap": [ "app/AppKernel.php", "app/AppCache.php" ]
    },
    ...
}
```

where :
* Vendor: your vendor folder name
* FieldTypeBundle: your FieldType Bundle folder name
* FieldType: your FieldType name

example :

```json
{
    "name": "ezsystems/ezplatform",
    ...
    "autoload": {
        "psr-4": {
            "AppBundle\\": "src/AppBundle/",
            ...
            "Edgar\\FooFieldTypeBundle\\": "src/Edgar/FooFieldTypeBundle/src/bundle/",
            "Edgar\\FooFieldType\\": "src/Edgar/FooFieldTypeBundle/src/lib/",
            ...
        },
        "classmap": [ "app/AppKernel.php", "app/AppCache.php" ]
    },
    ...
}
```

After all, you should dump composer autoload by executing this command :

```
composer dumpautoload
```
