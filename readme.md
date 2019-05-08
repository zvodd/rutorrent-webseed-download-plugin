ruTorrent Plugin to create a webseed only torrent.

Adds a rightclick menu item to ruTorrent to download a webseed torrent.


### Webseed Torrents
A webseed-only torrent is stripped of all tracker information and includes only a http "webseed".
Useful for downloading a private torrent from a seedbox completely intact with out affecting download/seed ratios. The torrent client will just just be downloading directly from a http server - i.e. usually your seedbox.

** This means you need ruTorrent's downloads folder to be served over http.** 
Although it does not need to be indexable (list files).

#### Caveats 
Webseed torrents can experience poor speeds in libtorrent based clients.
Client known to get good speeds are [Aria2](https://aria2.github.io/), Aria2 based download managers like [uGet](http://ugetdm.com), and Free Download Manager (windows).


### Install

`git clone` into rutorrent plugins directory.
In ruTorrent's `conf.php` file, create the `webseedurl` variable:
```
$webseedurl = "http://example.com/downloads";
```
Replace `example.com`with your server's domain name or IP address.

The URL must be a "live" http/https location with the `path` component mapped to ruTorrent's download folder. i.e. served with Apache / nginx / some httpserver

**Note**:  http auth URLs work and follow this format:

```
$webseedurl = "http://username:password@example.com/downloads";
```
