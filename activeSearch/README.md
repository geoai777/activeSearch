# Active filter plugin for wordpress

## Installation
Plugin was wrote fasthand, so there is no install package as of yet.
You need:
 1. Go to `/wp-contents/plugins`
 2. `mkdir activeSearch`
 3. Copy php file from this repo to that dir.
 4. Go to wordpress control panel an enable plugin.
 
 Now there are two ways:
 - In function `prSearch()` describe the way you want to interact with API
 - Use options interface to make API connector adjustable to different needs.
 
 Adjust styles in `porco_search()` function to your liking.
 
 ## Usage
 Just put shortcode [activeSearch] on page where you want search form + results.