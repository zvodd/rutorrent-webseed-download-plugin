* __ruTorrent Plugin to create a webseed only torrent.__

* __Adds a right-click menu item to ruTorrent for downloading a webseed torrent.__

### Webseed Torrents

Useful for downloading a private torrent from a seedbox completely intact with out affecting download/seed ratios.
The main reason for doing this is to facilitate downlading a completey intact folder stucture of the torrent and being able to use a local torrent client to do the last mile file transfer to you local machine, rather than using FTP or rSync.

A webseed-only torrent is stripped of all tracker information and includes only a http(s) "webseed".
The local torrent client makes use of the web seed torrent by downloading files of the torrent, directly via http from your seedbox.

##### Caveats 

You should be VERY careful about who you share your webseed.torrent files with.
THEY SHOULD NEVER BE SHARED PUBLICLY - as they contain a direct link to your private seedbox, (AND depending on your configuration, may contain Authetication Credentials - HTTP Auth username and password.)

Webseed torrents can experience poor speeds in libtorrent based clients.
Clients known to get good speeds are Deluge 2, [Aria2](https://aria2.github.io/), [uGet](http://ugetdm.com) with Aira2 backend, and also Free Download Manager for Windows.

**Configuration requires ruTorrent's downloads folder to be served over http.**  e.g. accessable by URL like `http://seedbox.example.com/downloads`
However, the URL path does not need to be indexable (list folder contents). See [zvodd/webseeder](https://github.com/zvodd/webseeder) project for a stand alone http(s) server that can fulfill this requirement (necessitates custom ports.)

### Install

Copy this repo into your `/srv/rutorrent/plugins` folder named `webseedsource`.
From `/srv/rutorrent/plugins` directory run:
```
git clone https://github.com/zvodd/rutorrent-webseed-download-plugin.git webseedsource
``` 
You should now have a `/srv/rutorrent/plugins/webseedsource` directory.

#### Configuration

Edit the variables in `conf.php`.

##### WebSeed URL

This is put inside the torrent file itself and is how your local torrent file finds actually accesses the payload data.

Set the `$weebseedurl` variable to point to the base URL of your web server's download location. e.g.

```
$webseedurl = "https://example.com/downloads";
```

Replace `example.com`with your server's domain name or IP address.
Also replace `/downloads` with the correct website path to your downloads.
Prefix with `http://` or `https://` appropriate to your server config.

The URL must be a "live" http/https location with the `path` component mapped to ruTorrent's download folder. i.e. served with Apache or nginx.

##### HTTP Auth (Optional)

This is required if the web address to your downloads is behind HTTP Auth. If not; DO NOT include the `username:password` part in the line.

**Note**:  Only non-Auth or Auth Basic HTTP URIs work (for torrent clients tested), Auth Basic URIs follow this format:

```
$webseedurl = "https://username:password@example.com/downloads";
```

#####  Set local path of torrent folder.

Finally modify `$webseedbase` to point to the OS folder path of your served rtorrent downloads folder.
For example if seedbox's url for serving `https://example.com/downloads` gets files from `/home/user/rtorrent_downloads` then the line should be:

```
$webseedbase = "/home/user/rtorrent_downloads";
```


### Apache Config for HTTP Auth Digest configs.

**Tested with QuickBox.io seedboxes**

If you have ruTorrent `fileshare` plugin, you can create a symlink to your rutorrent downloads folder in the fileshare plugin's folder: 
```
ln -s /home/user/rtorrent_downloads /srv/rutorrent/home/fileshare/webseed
```

Assuming it has an appropraite apache2 config entry that allows `FollowSymLinks` e.g. a file in `/etc/apache2/site-enabled/*.conf` similiar to this:

```
<Directory "/srv/rutorrent/home/fileshare">
    Options -Indexes +FollowSymLinks
    AllowOverride All
    Satisfy Any
</Directory>
```

In this case `$webseedurl` in `conf.php` should be something like `"http://example.com/fileshare/webseed"`


### Glossary of example variables 

All instances of above examples directories and paths are common examples, that very likley wont match your seedbox setup excatly. An explanation for each follows:

The main download destination of rTorrrent / ruTorrent, used as the value for `$webseedbase` in `conf.php`
```
/home/user/rtorrent_downloads
```

The install directory of ruTorrent:
```
/srv/rutorrent
``` 

ruTorrent's plugins directory, it should be full of plugins.
```
/srv/rutorrent/plugins
```

The directory that ruTorrent-fileshare-plugin uses, contains `share.php`:
```
/srv/rutorrent/home/fileshare
```

URL using HTTP Auth Basic with Username and password in URL:
```
https://username:password@example.com/downloads
```
