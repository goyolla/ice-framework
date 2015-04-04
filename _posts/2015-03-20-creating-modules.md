---
layout: post
title: Creating Modules
---

There are two types of modules in IceFramework.
	- **admin**: modules which are accessible by Admins and sometimes Managers
	- **modules** modules which can be accessed by all user levels. Any user how doesn't have a Profile attached to them won't have access to these modules

##Module folder structure

<pre>
+-- _config.yml
+-- _drafts
|   +-- begin-with-the-crazy-ideas.textile
|   +-- on-simplicity-in-technology.markdown
+-- _includes
|   +-- footer.html
|   +-- header.html
+-- _layouts
|   +-- default.html
|   +-- post.html
+-- _posts
|   +-- 2007-10-29-why-every-programmer-should-play-nethack.textile
|   +-- 2009-04-26-barcamp-boston-4-roundup.textile
+-- _data
|   +-- members.yml
+-- _site
+-- index.html
</pre>

##meta.json

{% gist 8b8aa02c917aadf6a6be %}
