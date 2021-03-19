@{md.include::file:elements/header.en.md}

# Commands

Commands are called by Siwiki during [crafting process](index.!) giving you more control over your wiki.

There are two types of commands:
  - **MD Commands** - called on MD document (before turning it into HTML),
  - **HTML Commands** - called after converting MD document into HTML.

All commands have standard format, which is:

| Order |  Element  |    Clarification                                    |
| ----- | --------- | --------------------------------------------------- |
|     1 |     @{    | At and curly opening bracket marks command starting |
|     2 |   prefix  | `md` or `html` depending on command type            |
|     3 |    name   | Command name (as listed below)                      |
|     4 | arguments | Extra arguments send to command                     |
|     5 |     }     | Finishing character                                 |

If you want to write a command that will not be called, but left intact - just add two backslashes (`\\`) between at (`@`) and curly opening bracked (`{`) resulting in:
```
@\\{...
```

## MD Commands

Possible commands:
  - [md.include](en-md-include.!) - that includes HTML file (header or footer)

## HTML Commands

Possible commands:
  - [html.include](en-html-include.!) - that includes HTML file (header or footer)
  - [html.addClassToTag](en-html-addClassToTag.!) - adds given class to all tags of given name

@{html.include::file:elements/footer.en.html}
