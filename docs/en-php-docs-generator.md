@{md.include::file:elements/header.en.md}

# PHP Docs Generation

Siwiki has ability to scan through well prepared code (following `PHP CS` set of rules) to identify:
  - classes (and their properties and methods),
  - `composer.json` file.

At the ends it is able to prepare `JSON` file with documentation of whole PHP code. This `JSON` file is then used to create md documents. See here for [sytax of that file](en-docs-json-syntax.!).

## How to generate `JSON` documentation

Use following code.

```php
$json = new PhpDocGenerator();
$json->setDstUri('../docs.md/');
$json->addApp('przeslijmi/siwiki', 'src/')->setComposerJson('composer.json');
$json->addApp('company/app', 'vendor/company/app/src')->setComposerJson('vendor/company/app/composer.json');
$json->create();
```

Destination uri is where the final `JSON` file has to be generated. File name is unchangeable, ie. `_docs.json`.

Then you can add app's that has to be included in documentation, by defining:
  - name of the app - used to name a main node in `_docs.json`,
  - top directory with PHP files - Siwiki will scan recursively through this directory to find all PHP files (and ignore other extensions)
  - optionally `composer.json` uri - which (if defined) will be incorporated with Docs.

## Inlclude Docs during crafting

When docs is ready remember to include it during crafting process - as shown on **line 4 below**.

```php
$siwiki = new Siwiki();
$siwiki->setSrcUri('../docs.md/');
$siwiki->setDstUri('../docs.html/');
$siwiki->setInclDoc(true);
$siwiki->craft();
```

@{html.include::file:elements/footer.en.html}
