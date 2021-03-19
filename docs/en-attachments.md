@{md.include::file:elements/header.en.md}

# Attachments

Attachments are files that are copied to destination uri during [crafting process](index.!). Purpose of this functionality is transferring CSS, JS and binary files - but it can be used to transfer any type of files.

To include attachments in crafting process put them in `attachments` folder directly inside source directory.

So in this example
```php
$siwiki = new Siwiki();
$siwiki->setSrcUri('../docs.md/');
$siwiki->setDstUri('../docs.html/');
$siwiki->craft();
```
attachmets must be located in `../docs.md/attachments/` directory.

Full contents of attachmets folder will be copied to destination folder `root`.

@{html.include::file:elements/footer.en.html}
