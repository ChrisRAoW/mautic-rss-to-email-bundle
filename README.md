# Mautic RSS to E-mail

## [Description](id:description)
The Mautic RssToEmailBundle is a Mautic plugin that allows you to generate e-mails from a RSS-feed.

### Purpose
Send out an e-mail with for example to latest posts of your blog.

### Compatibility
This plugin has been tested with v2.14.1 of Mautic.

### Features
 * Set the number of posts you want to display
 * Create a custom template for the items in the feed
 * Use images from the feed
 * Format dates

## [IMPORTANT: Please check before install](id:pre-installation)
The method that is used to install this plugin requires Mautic's composer.json in the root of the Mautic installation. If you installed Mautic from Github (https://github.com/mautic/mautic), you should be fine. If you installed Mautic from a zip downloaded from the Mautic website, please check if the composer.json exists. If not, download the composer.json from Mautic's Github and upload it before installing the plugin.

## [Installation](id:installation)
Currently only installation with composer is supported. This since it is depending on a third-party library to parse the feed.

1. Login into the terminal (ssh) and cd to the root directory of your mautic installation
2. Install plugin with composer
```
composer require raow/mautic-rss-to-email-bundle
```

3. In the Mautic GUI, go to the gear and then to Plugins.
4. Click on the "Install/Upgrade Plugins" button
5. You should now see the "Rss To Email" in your list of plugins.

## [Usage](id:usage)
Use the "code mode" slot of the froala e-mail editor. In the content of the slot set to following content:

```
{feed url="<<FEEDURL>>"}
    {feeditems count="3"}
        <h3>{feeditem:title}</h3>
        <p><small>{feeditem:date format="d-m-Y H:i"}</small></p>
        <p>{feeditem:description}</p>
        <p><img src="{feeditem:image}"></p>
    {/feeditems}
{/feed}
```

This should give a basic setup to start with.

### The following tags can be used in the {feed} block:

#### {feedinfo:title}
Returns: title of the feed

#### {feedinfo:url}
Returns: url of the feed

#### {feedinfo:description}
Returns: description of the feed


### The following tags can be used in the {feeditems} block:

#### {feeditem:title}
Returns: title of the post

#### {feeditem:link}
Returns: link to the post

#### {feeditem:content}
Returns: summarized content (desciption), when description is not available it will return the full content

#### {feeditem:content_full}
Returns: full content of the post

#### {feeditem:content_text}
Returns: summarized content (desciption), when description is not available it will return the full content. Tags are stripped.

#### {feeditem:content_full_text}
Returns: full content of the post stripped of tags

#### {feeditem:description}
Returns: summarized content (desciption) of the post

#### {feeditem:date}
Optional param: format {feeditem:date format="d-m-Y H:i"}
Returns: summarized content (desciption) of the post

#### {feeditem:author}
Returns: author name of the post

#### {feeditem:categories}
Returns: comma seperated list of the categories

#### {feeditem:image}
Returns: url of the image. Will check the enclosere and media tags of the xml.
