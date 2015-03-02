#WP-phpBB-Embed
wp-phpbb-embed is a sligtly inappropriately named WordPress plugin that adds a widget showing the most recent posts from a phpBB 3.1 bulliten board

##Version 0.2

***

##Requirements
- Wordpress ( > 4.1)
- phpBB ( > 3.1)

***

##Installation
Installation requires two parts: The WordPress plugin and the phpBB connectors.

###Wordpress Plugin:
There are 2 methods for getting the WP plugin .zip file:  
- The _Easy_ Way:
[Download](https://github.com/mike-teehan/wp-phpbb-embed/archive/master.zip) the .zip file from Github
- The **Better** Way:
Clone the git repository and build a custom .zip  
> $ git clone https://github.com/mike-teehan/wp-phpbb-embed.git  
> $ cd wp-phpbb-embed  
> $ ./makezip  

Once you have plugin .zip file:  
1. Login to /wp-admin/ and go to Plugins > Add New  
2. Upload plugin .zip file  
3. Enable WP-phpBB-Embed plugin  
4. Use Appearance > Widgets to add the widget to pages  

####Settings:

The WP-phpBB-Embed has 8 options that must be configured properly in order for the plugin to work. They are broken in to two sections:

#####Widget:
- Title: Set the title that will be shown above your recent posts widget instance  
- WP URL: The absolute URL of the WordPress site  
- phpBB URL: The absolute URL of the phpBB site  
- Recents URL: The absolute URL of recents.json.php on the phpBB site  

#####FakePress:
- Background color: The color for the the background like RRGGBB (no #)  
- Page Title: The &lt;title&gt; for the page  
- Header Image URL: Link to the image that will be diplayed in the header  
- Header Link URL: Link to where the users goes when they click the header  

###phpBB Integration:
- Copy recents.json.php and fakepress.php to the root of the phpBB installation (where config.php lives)  

***

##License

GPL v2
