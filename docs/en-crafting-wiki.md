@{md.include::file:elements/header.en.md}

# Crafting wiki

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

@{html.include::file:elements/footer.en.html}
