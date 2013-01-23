# RSS/Atom Feed plugin for [Pimcore](http://www.pimcore.org/) #

This [Pimcore plugin](http://www.pimcore.org/wiki/display/PIMCORE/Plugin+Anatomy+and+Design) is written in [PHP](http://php.net/manual/en/) and uses the [Zend Framework](http://framework.zend.com/manual/1.12/en/manual.html).

This plugin provides your Pimcore website with an RSS/Atom feed with an entry for every [document](http://www.pimcore.org/wiki/display/PIMCORE/Documents) you create. This is useful for websites that are document based and regularly add new documents.

## Features ##

* Supports both RSS and Atom feeds.
* Works for all Pimcore websites using documents.
* Easily configurable through Pimcore's website settings.
* Ability to set your own routes for the feed.
* Ability to exclude pages from feed.

## Installation ##

Open the Pimcore administration and click on the menu `Extras`, then `Download extensions`. Enter your Pimcore username and password and look for the RSS/Atom Feed in the list of plugins and click the Download icon on the right side.

If you are not using the Pimcore installer, you can simply copy the Feed directory into Pimcore's plugins directory.

After downloading Go to `Manage extensions` and click the `Install`-button for the RSS/Atom Feed.

The installation adds two [static routes](http://www.pimcore.org/wiki/display/PIMCORE/Custom+Routes+%28Static+Routes%29) and various website settings to your Pimcore website.

## Configuration ##

Go to [Website Settings](http://www.pimcore.org/wiki/display/PIMCORE/Website+Settings). If the RSS/Atom Feed plugin was successfully installed you should see various settings of which the name starts with "feed".

### feedDefaultTitle ###

The title of the feed. Any document that does not have a title will also get this title.

For example, if your name is Fred and you have website with recipes the title could be "`Fred's Recipes`"

### feedDocumentBody ####

The content of Pimcore documents is placed in editable fields. Here you should enter the name of the editable that contains the introduction, or main content of the document.

For example if your documents have [WYSIWYG-editable](http://www.pimcore.org/wiki/display/PIMCORE/WYSIWYG) like `<?php echo $this->wysiwyg('body') ?>` you enter "`body`" as the value.

### feedBaseUrl ###

The base URL should be the host name (domain) of your website. If you do not enter the *correct path* here the links in the RSS/Atom Feeds will not work!

For example, if your website is www.fredsrecipes.ext The value you enter here is `http://www.fredsrecipes.ext` (no slash at the end)

### feedLimit ###

The `feedLimit` describes how many entries the RSS/Atom Feed should display. The default is `25`, this is usually a good default. If you add a lot of documents you might want to increase the limit.

### feedDescription ###

The `feedDescription` setting is only used by the RSS-feed and should contain a general description of the RSS feed. You can also choose to leave it empty.

For example, if your name is Fred and you have website with recipes the description could be "`Fred's cooking Recipes for home chefs that enjoy a hands on approach.`"

### feedAuthor ###

Enter the name of the author of the documents here. If you leave the `feedAuthor` empty it will default to "`unknown`" because a value is required by the Atom specification.

## Enabling the feed ##

After entering the data you can access the feeds with a web browser at the following paths:

* /feed/atom
* /feed/rss

There are now two feeds that are basically the same. If you don't need the RSS-feed for any particular purpose, just stick to the Atom-feed.

You can disable one of the feeds simply by going to `Static Routes` in the `Settings` menu and remove one of the static routes. You can change the static route if you need to have the feed available at another location for backwards compatibility.

The final step is to include a link to your feed in the `<head>`-element of your website. Like said before, it is recommended that you only add the Atom-feed as this is more modern and widely supported:

```html
<link href="/feed/atom" rel="alternate" type="application/atom+xml">
<link href="/feed/rss" rel="alternate" type="application/rss+xml">
````

For a standard Pimcore website you should add this in the file `/website/views/layouts/layout.php`

## Frequently asked questions ##

### Do unpublished documents appear in the RSS/Atom feed? ###
No, only published documents appear in the feeds.

### Can I make other pages that work with objects and static routes appear in the feed? ###
No, the RSS/Atom-feed does not support this. However, you can take a look at the code of the `FeedController` to see how you can create your own RSS/Atom Feeds in Pimcore.

### Why do all entries in the feed have the default title? ###
The plugin uses the document title from the "Settings" tab that every document has. It is recommended that you enter a descriptive title here and also a description, as this is used in the Atom-feed as well.

### How do I prevent certain pages from appearing in the feed? ###
By default all *published* documents appear in the feed. Forone not to appear in the feed you can add a [property](http://www.pimcore.org/wiki/display/PIMCORE/Properties) of the type `checkbox` with the name `showInFeed` to the document and leave the checkbox *unchecked*. To make the document appear in the feed again simply check the checkbox or remove the property.

###In what language is this plugin available?###
This plugin can be used with websites/documents of any language. UTF-8 encoding is used in the feeds. Installation messages (for administrators only) are available in English and Dutch.

## License ##

This plugin is released under the New BSD License, which is included here:

Copyright (c) 2013, Schuttelaar & Partners
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the <organization> nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

