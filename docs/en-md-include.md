@{md.include::file:elements/header.en.md}

# MD Include

Includes given MD file. One of [commands](en-commands.!) called on MD contents (before parsing to HTML).

## Example

```
@\\{md.include::file:elements/header.en.md}
```

Params to be used:
  - `file` - uri to MD file to be included

## Comments

MD files can have further both HTML and MD commands, while HTML file can have only HTML commands.

@{html.include::file:elements/footer.en.html}
