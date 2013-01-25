<?php
/*
 * LightboxThumbs extension, version 0.1.2
 * by Alexander, http://www.mediawiki.org/wiki/User:Alxndr, September 2007
 * Displays thumbnailed images full-size in window using Lokesh Dhakar's Lightbox 2 (http://www.huddletogether.com/projects/lightbox2/).
 * 
 * Licensed under Creative Commons Attribution-NonCommercial license 3.0: http://creativecommons.org/licenses/by-nc/3.0/
 * 
 * Bugs: will make very large images take over your screen! doesn't do any resizing
 *       if there are multiple galleries on one page, they are treated as being part of one big slideshow
 *       (probably) breaks on images with / in the name
 * 
 * Todo: make galleries register as separate slideshows
 *       change thumbnail caption source to preserve markup, like galleries
 */
 
if ( !defined( 'MEDIAWIKI' ) )
        die( 'This is a MediaWiki extension, and must be run from within MediaWiki.' );
 
$wgExtensionCredits['other'][] = array(
        'name'=>'LightboxThumbs',
        'url'=>'http://www.mediawiki.org/wiki/Extension:LightboxThumbs',
        'author'=>'[http://www.mediawiki.org/wiki/User:Alxndr Alexander], alxndr@<span style="display:none"> no spam, please </span>gmail.com',
        'description'=>'Displays full-size images with [http://www.huddletogether.com/projects/lightbox2/ Lightbox 2] when clicking on thumbnails.',
        'version'=>'0.1.2'
);
 
$wgHooks['BeforePageDisplay'][] = 'efBeforePageDisplay'; # not the best way to do it, but can't subclass Linker...
 
function efRewriteThumbImage($matches) {
        ## [1] = <a> front matter
        ## [2] = URL to image description page, no server
        ## [3] = caption
        ## [4] = <a> end matter, right before <img>
        ## [5] = <img> tag
        global $wgOut;
        $title = Title::newFromText(rawurldecode($matches[2])); ## jumping through hoops
        $repo = RepoGroup::singleton()->getLocalRepo()->newFile($title);  ## ughhh
        return         $matches[1].' href="'.$repo->getUrl().'" class="image" rel="lightbox" title="'
                        .htmlspecialchars( $wgOut->parse("'''[[:".$title->getFullText()."|".$title->getText()."]]:''' ").$matches[3] )
                        .'" '.$matches[4].$matches[5]; ## wonder how server-heavy that was
}
 
function efRewriteGalleryImage($matches) {
        ## [1] = front matter
        ## [2] = URL to image description page, no server
        ## [3] = from after opening of <a> to before caption
        ## [4] = caption
        global $wgOut;
        $title = Title::newFromText(rawurldecode($matches[2]));
        $repo = RepoGroup::singleton()->getLocalRepo()->newFile($title);
        return         $matches[1].' href="'.$repo->getUrl().'" class="image" rel="lightbox[gallery]" title="'
                        .htmlspecialchars( $wgOut->parse("'''[[:".$title->getFullText()."|".$title->getText()."]]:''' ").$matches[4] )
                        .'" '.$matches[3].$matches[4];
}
 
function efBeforePageDisplay($out) {
        $out->addScript('<script type="text/javascript" src="skins/LightboxThumbsFiles/js/prototype.js"></script>');
        $out->addScript('<script type="text/javascript" src="skins/LightboxThumbsFiles/js/scriptaculous.js?load=effects"></script>');
        $out->addScript('<script type="text/javascript" src="skins/LightboxThumbsFiles/js/lightbox.js"></script>');
        $out->addStyle('LightboxThumbsFiles/css/lightbox.css'); ## turns into skins/LightboxThumbsFiles/css/lightbox.css
 
        ## thumbnails
        $pattern = '/(<a[^>]+?)\s*href="[^"]*?\/?([^"\/]+)"\s*class="image"\s*title="([^"]+)"\s*([^>]*>)\s*(<img[^>]+?class="thumbimage"[^>]*>)/';
        ##            $1: start              $2: img desc url                      $3: captn  $4: before img         $5: img tag
        $results = preg_replace_callback($pattern, 'efRewriteThumbImage', $out->getHTML());
        $out->clearHTML();
        $out->addHTML($results);
 
        ## galleries
        ## need separate cause caption isn't in the <a>, argh
        $pattern = '/(<div class="gallerybox"[^>]+>\s*<div class="thumb"[^>]+>\s*<div[^>]+>\s*<a[^>]+?)\s*href="[^"]*?\/?([^"\/]+)"\s*class="image"\s*title="[^"]+"([^>]*>.+?<div class="gallerytext">)\s*(.+?)\s*<\/div>/s';
        ##             $1: start                                                                                     $2: img desc url                              $3: after open <a>, before caption   $4: caption
        $results = preg_replace_callback($pattern, 'efRewriteGalleryImage', $out->getHTML());
        $out->clearHTML();
        $out->addHTML($results);
 
        return true;
}
