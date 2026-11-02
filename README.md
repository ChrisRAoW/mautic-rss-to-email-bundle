# Mautic RSS to E-mail

## [Description](id:description)
The Mautic RssToEmailBundle is a Mautic plugin that allows you to generate e-mails from a RSS-feed.

### Purpose
Send out an e-mail with for example to latest posts of your blog.

### Compatibility
Version 2.x of this plugin requires Mautic 7.x and PHP 8.4 or higher.

For Mautic 2.x, 3.x and 4.x use version 1.x of this plugin.

### Features
 * Set the number of posts you want to display
 * Create a custom template for the items in the feed
 * Use images from the feed
 * Format dates
 * Reverse view

## [Installation](id:installation)

1. Download the plugin from github
2. Move / Upload folder to the plugins directory. Name the folder `MauticRssToEmailBundle`
3. Clear the cache: `php bin/console cache:clear`
4. In the Mautic GUI, go to the gear and then to Plugins.
5. Click on the "Install/Upgrade Plugins" button (or run `php bin/console mautic:plugins:reload`)
6. You should now see the "Rss To Email" in your list of plugins.

The `vendor` folder (SimplePie) is shipped with the plugin. When you change `composer.json`, run `composer update --no-dev` inside the plugin folder to rebuild it.

## [Usage](id:usage)
Use a "Custom code" block in the GrapesJS e-mail builder (or the code editor). In the content of the slot set to following content:

```
{feed url="<<FEEDURL>>"}
    {feeditems}
        <h3>{feeditem:title}</h3>
        <p><small>{feeditem:date format="d-m-Y H:i"}</small></p>
        <p>{feeditem:description}</p>
        <p><img src="{feeditem:image}"></p>
    {/feeditems}
{/feed}
```

This should give a basic setup to start with.

### The following tags can be used in the {feed} block:

* `{feedinfo:title}`  
Returns: title of the feed

* `{feedinfo:url}`
  Returns: url of the feed

* `{feedinfo:description}`
  Returns: description of the feed

### The following tags can be used in the {feeditems} block:

#### Optional param:
* Batch mode

  By default batch-mode will be enabled. This means that the feed-tags will only be replaced once per batch. When you disable batch-mode, the feed will be parsed for every single e-mail. So if you have a list of 1000 addresses, the feed will be requested 1000 times. Because of the high number of requests in a short timeframe it could cause the server where the feed is on to go down. *So handle with care!*

  `{feed batch="0"}` - Will DISABLE batch-mode on the feed.

* Count  
  `{feeditems count="3"}` - Returns only the first N items from the feed

* Offset  
`{feeditems count="3" offset="2"}` - Skips the first N items from the feed

* Reverse  
`{feeditems reverse="1"}` - Returns resuls in reversed view

* Shuffle  
`{feeditems count="3" shuffle="1"}` - Returns results in shuffled (random) view, can be used in combination with the `count` parameter

__Merge tags:__  
* `{feeditem:title}`  
Returns: title of the post

* `{feeditem:link}`  
Returns: link to the post

* `{feeditem:content}`  
Returns: summarized content (desciption), when description is not available it will return the full content

* `{feeditem:content_full}`  
Returns: full content of the post

* `{feeditem:content_text}`  
Returns: summarized content (desciption), when description is not available it will return the full content. Tags are stripped.

* `{feeditem:content_full_text}`  
Returns: full content of the post stripped of tags

* `{feeditem:description}`  
Returns: summarized content (desciption) of the post

* `{feeditem:date}`  
Optional param: format {feeditem:date format="d-m-Y H:i"}  
Returns: summarized content (desciption) of the post

* `{feeditem:author}`  
Returns: author name of the post

* `{feeditem:categories}`  
Returns: comma seperated list of the categories

* `{feeditem:image}`  
Returns: url of the image. Will check the enclosere and media tags of the xml.

* `{feeditem:custom:KEY}`  
Returns: value of custom field in RSS feed.

  To use this functionality you have to:
  1. Add this to the xmlns definitions in your RSS-feed: `xmlns:mautic="https://www.mautic.org/rss/mautic/"`  
     
     For example:
     ```
     <?xml version="1.0" encoding="UTF-8"?><rss version="2.0"
	  xmlns:content="http://purl.org/rss/1.0/modules/content/"
	  xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	  xmlns:dc="http://purl.org/dc/elements/1.1/"
	  xmlns:atom="http://www.w3.org/2005/Atom"
	  xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	  xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	  xmlns:mautic="https://www.mautic.org/rss/mautic/">
  
     <channel>
     ...
     ```

  2. Add the custom field to your RSS-feed with the following syntax:   
     `<mautic:location>New York</mautic:location>`

  3. Inside your mail template use the following tag:  
     `{feeditem:custom:location}`

### Icon

<div>Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
