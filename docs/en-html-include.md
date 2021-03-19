@{md.include::file:elements/header.en.md}

# HTML Include

Includes given MD file. One of [commands](en-commands.!) called on HTML contents (after parsing from MD).

## Example

```
@\\{html.include::file:elements/header.en.html}
```

Params to be used:
  - `file` - uri to HTML file to be included

## Comments

HTML file can have only HTML commands, while MD files can have further both HTML and MD commands.

@{html.include::file:elements/footer.en.html}
