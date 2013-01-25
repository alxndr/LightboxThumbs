# LightboxThumbs

LightboxThumbs incorporates Lokesh Dhakar's fullsize image viewer [Lightbox 2](http://www.huddletogether.com/projects/lightbox2/) into [MediaWiki](http://www.mediawiki.org/wiki/MediaWiki)'s thumbnails and image galleries.

When a user clicks on a thumbnailed image or an image in a gallery, the page darkens and the image is displayed full-size "on top of" the page. A caption below the image includes the image's title and a link to its description page, along with the caption on the thumbnail, if any. Clicking anywhere off the image returns everything to normal.

Images in galleries are treated as being part of a slideshow, and have "previous" and "next" images.


## Installation for MW >= 1.16 \*

0.  `cd extensions/ && git clone https://github.com/alxndr/LightboxThumbs.git`
1.  Download Lokesh Dhakar's [Lightbox 2](http://lokeshdhakar.com/projects/lightbox2/releases/), rename the directory to "lightbox", and put it in your `extensions/` directory. JS, CSS, and image files need to be accessible in a browser.
2.  Edit `extensions/lightbox.js`: change `fileLoadingImage` and `fileBottomNavCloseImage` so they're indicating where the images actually are.
3.  Edit `LocalSettings.php` to add:

        $lightboxThumbsFilesDir = '/extensions/lightbox';
        include_once("$IP/extensions/LightboxThumbs.php");

\* If for some reason you're running MW 1.10 or earlier, try using [v0.1.2](https://github.com/alxndr/LightboxThumbs/tree/v0.1.2). If you're between 1.11 and 1.15, try [v0.1.3](https://github.com/alxndr/LightboxThumbs/tree/v0.1.3a).

## Bugs

...are kept track of with [Issues](https://github.com/alxndr/LightboxThumbs/issues)


## Caveat ūsor

This was originally developed on an installation of 1.11 with an old custom skin and a bunch of extensions and a lot of general hackery. Unforeseen behavior is a very real possibility.

That said, let me know and I'll try to help fix anything that goes wrong.


## Props

Thanks to Piotr, Timmy, and Georges for helping me debug and test new versions of v0.1.3.

Thanks to Kinsey Moore, [Rgoodermote](http://www.mediawiki.org/wiki/User:Rgoodermote), [Michael Markert](http://www.mediawiki.org/wiki/User:Auco), and [Aczs](http://www.mediawiki.org/wiki/Special:Contributions/Aczs) for taking the ball and running to create v0.1.4, with bug fixes and tweaks to get it working in MW 1.16.

