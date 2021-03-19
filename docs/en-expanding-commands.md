@{md.include::file:elements/header.en.md}

# Expanding commands

It is possible to add own commands to [commmands defined inside Siwiki](en-commands.!).

Prepare new command in separate PHP class, implement [`CommandsInterface`](class-Przeslijmi_Siwiki_Commands_CommandsInterface.!) and finally add this commnad to `PRZESLIJMI_SIWIKI_HANDLE_EXCEPTIONS` configuration ([see configurations info](en-configurations.!)).

You are now able to use this command inside `MD` documents.

@{html.include::file:elements/footer.en.html}
