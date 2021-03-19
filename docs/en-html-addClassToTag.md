@{md.include::file:elements/header.en.md}

# HTML AddClassToTag

Adds CSS class to every instance of given HTML tag. One of [commands](en-commands.!) called on HTML contents (after parsing from MD).

## Example

```
@\\{html.addClassToTag::tag:table;class:myClass}
```

Params to be used:
  - `tag` - to which HTML tag class is to be added
  - `class` - CSS class to be added

## Comments

You can only call this command once per file and tag. If you wan't to add two or more classes to tag you have to call command once with multiple classes separated with space.

@{html.include::file:elements/footer.en.html}
