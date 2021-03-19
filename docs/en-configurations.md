@{md.include::file:elements/header.en.md}

# Configurations

You can define configurations in `siwki/.env/.env.php` file.

## `PRZESLIJMI_SIWIKI_COMMANDS`

An array of possible commands that Siwiki can use. It can be expanded by the user at personal needs. Array has to be formated with:
  - key string containg command name (with `md.` or `html.` prefix)
  - value string containg class that serves this command.

Read more on how to [expand commands serving](en-expandingCommands.!).

## `PRZESLIJMI_SIWIKI_HANDLE_EXCEPTIONS`

For development set to `true`, for production set to `false`.

When set to `true` it catches all uncatched exceptions, echo their message and prevent from further operations. On production environment that is a bad behaviour while it leads to die after each exception and prevents from propagating them.

@{html.include::file:elements/footer.en.html}
