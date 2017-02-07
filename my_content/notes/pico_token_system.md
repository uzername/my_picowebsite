---
Title: PICO CMS: Control access to pages using Tokens
---
# Protecting content and providing access using tokens{.content-h1}
One may come into a need to share some info only with the selected group of people, in the private manner. Or just keep it private and accessible only for self. PICO CMS does not have such capability from the box...

*    The idea is to make a tokens, define their md5 hashsums in tokenlib file. 
*    It's OK to pass tokens as GET parameters. If GET parameter does not contain token, then display page inviting to enter a token for the article. 
*    The next step is to display page after successful entry of token.

We need to define some metadata scheme components for tokens handling.

*    `[TRUE | FALSE] UseToken`
*    `[<String>]TokenID`

Pico::getPlugins scans the plugins folder, retrieves all php files in it, so any helping processors should be moved to /lib/ subfolder! Probably it would be OK to use the single module file for processing purposes. In this case the form's target is left blank.

The similar plugin is [Pico Users](https://github.com/nliautaud/pico-users) although it is obsolete and used for earlier version of Pico.