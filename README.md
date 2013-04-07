wp-cselian
==========

Various plugins and themes for use in wordpress.

Companion
-----------
The WP Companion is a wordpress plugin that does a host of things.

* Megamenu with custom nav structure.
* Shortcode for links to posts, documents, image with links
* Shortcode for gallery, html snippets.
* Widgets for Related links (parent, sibling, children, category posts)
* Font resizer working with jquery.cookies

See more: https://github.com/ImranCS/wp-cselian/wiki/Companion

Page Generator
---------------
Generate multi level Pages from excel

See more: https://github.com/ImranCS/wp-cselian/wiki/Generator

WP Admin
----------
Rather than the multisite configuration, I read $_SERVER['HTTP_HOST'] and vary the wordpress database.
This keeps them isolated and is especially useful for staging websites that have to be moved eventually.
Plugins can be updated at once, the only caveat is that its hard to track which plugins are in use on which site.

See more: https://github.com/ImranCS/wp-cselian/wiki/Admin

In Action: http://cselian.com/blog/wp-content/plugins/cs-admin/ms-plugins.php
