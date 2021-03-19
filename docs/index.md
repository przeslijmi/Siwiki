@{md.include::file:elements/header.en.md}

# Siwiki Docs

**Siwiki** is a tool that helps you generate complete Wiki of an app - that incorporates both written documents with standard documentation that comes from PHP code itself.

## Simplest example of crafting process

### Parsing PHP files - and generating PHP Docs

With Siwiki you can add to wiki full PHP documentation of your code - even from multiple apps that you use as onw dependencies, created directly by Siwiki itself during [PHP Docs generation](en-php-docs-generator.!).

```php
$json = new PhpDocGenerator();
$json->setDstUri('../docs.md/');
$json->addApp('przeslijmi/siwiki', 'src/')->setComposerJson('composer.json');
$json->addApp('company/app', 'vendor/company/app/src')->setComposerJson('vendor/company/app/composer.json');
$json->create();
```

As a result of above - `_docs.json` file will be created at `../docs.md/` directory which will be ready to be used on next proces, which is crafting.

[JSON with php documentation](en-docs-json-syntax.!) can be also used for your other purposes.

### Crafting wiki

With Siwiki you can craft HTML wiki basing on two sources:
  - prepared by hand `MD` files,
  - `JSON` PHP documentation prepared in previous step.

Therefore you are able to create complete book of your app - that groups in one place both free text about your app (multilingual also) and code documentation - resulting in final **app almanach**.

```php
$siwiki = new Siwiki();
$siwiki->setSrcUri('../docs.md/');
$siwiki->setDstUri('../docs.html/');
$siwiki->setInclDoc(true);
$siwiki->craft();
```

After **crafting** as shown above all md files in source uri will be converted to html files, but also:
  - some [special commands](en-commands.!) will be used to boost crafting,
  - [attachments](en-attachments.!) will be copied to destination uri.

Read more on [crafting wiki](en-crafting-wiki.!).

## Dependencies

To function fully Siwiki depends on:
  - `erusev/parsedown` - [Better Markdown Parser in PHP](https://github.com/erusev/parsedown)

## Configuration

Configuration lets you control the crafting process even better and also [expand commands](en-expanding-commands.!) by your own.

[See full list of configarations.](en-configurations.!).

## Documentation

See complete code documentation generated automatically by this **Siwiki**:
  - [przeslijmi/siwiki Documentation](namespace-Przeslijmi_Siwiki.!)
  - [przeslijmi/siwiki Exceptions](namespace-Przeslijmi_Siwiki_Exceptions.!)
  - [Index of all classes](index-all-classes.!)
  - [Index of all methods](index-all-methods.!)

@{html.include::file:elements/footer.en.html}
