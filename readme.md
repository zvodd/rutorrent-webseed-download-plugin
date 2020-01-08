ruTorrent Plugin to create a webseed only torrent.

Adds a rightclick menu item to ruTorrent to download a webseed torrent.


### Webseed Torrents
A webseed-only torrent is stripped of all tracker information and includes only a http "webseed".
Useful for downloading a private torrent from a seedbox completely intact with out affecting download/seed ratios. The torrent client will just just be downloading directly from a http server (the seedbox itself).

** This means you need ruTorrent's downloads folder to be served over http.** 
However, the path does not need to be indexable (list folder contents).

#### Caveats 
Webseed torrents can experience poor speeds in libtorrent based clients.
Clients known to get good speeds are [Aria2](https://aria2.github.io/), [uGet](http://ugetdm.com) with Aira2 backend, and also Free Download Manager for Windows.


### Install

Copy this repo into your `rutorrent/plugins` folder named `webseedsource`.
`git clone https://github.com/zvodd/rutorrent-webseed-download-plugin.git webseedsource` in `rutorrent/plugins`directory. You should now have a `rutorrent/plugins/webseedsource` directory.

Edit the variables in `conf.php`.

```
$webseedurl = "http://example.com/downloads";
```
Replace `example.com`with your server's domain name or IP address.
Also replace `/downloads` with the correct website path to your downloads.

The URL must be a "live" http/https location with the `path` component mapped to ruTorrent's download folder. i.e. served with Apache or nginx.

**Note**:  http auth URLs work and follow this format:

```
$webseedurl = "http://username:password@example.com/downloads";
```
This is required if the web address to your downloads is behind HTTP Auth. If not; DO NOT include the `username:password` part in the line.

Finally modify `$webseedbase` to point to the OS folder path your servered downloads.
For example if seedboxes url `http://example.com/downloads` gets files from `/home/user/rtorrent_downloads` then the line should be:

```
$webseedbase = "/home/user/rtorrent_downloads";
```