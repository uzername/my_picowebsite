---
Title: PICO CMS: better styling in text
---
#Adding styles to markdown tags{.content-h1}

The problem is that the Markdown renderer returns page styled as markdown tags with plain html tags, without any classes or IDs. It makes it harder to style everything properly. There's no good to define styles for plain html tags. It's much better to define styles for some class in html, or for id.

Quick research shows that Markdown renderer is located in vendor/erusev folder. The real parsing process is done in `public function parseFileContent($content)` - Parses the contents of a page using __ParsedownExtra__. By doing further research on [MarkdownExtra](https://michelf.ca/projects/php-markdown/extra/#spe-attr) is seen that there's a support of extended set of markdown features.
~~~ {.content-codeblock}
The id, multiple class names, and other custom attributes can be combined by putting them all into the same special attribute block:
## Le Site ##    {.main .shine #the-site lang=fr}
~~~