/*************************************************
	This is the "Offline page" service worker
*************************************************/

// Name of Data Cache
var dataCacheName = "em-data";

// Name of the Cache
var cacheName = "em";

// Defines which files to cache locally
var filesToCache = [
  ".",
  "manifest.json",
  "sw.js",
  "sw-reg.js",
  "offline.html",
  "Offline.png",
  "../theme/css/bootstrap.css",
  "../theme/css/bootstrap-theme.css",
  "../images/Logo.png",
  "../images/TLogo.png",
  "../images/Offline.png",
  "../theme/js/bootstrap.js",
  "../theme/js/jquery.min.js"
];

/*
	Install stage
	- Opens the cache or creates it if it doesn't exist
	- Sets up the offline page in the cache
*/
self.addEventListener('install', function(event)
{
  var offlinePage = new Request('offline.html');
  event.waitUntil(
  fetch(offlinePage).then(function(response)
  {
    return caches.open(cacheName).then(function(cache)
    {
      console.log('[ServiceWorker] Cached offline page during Install. '+ response.url);
      return cache.put(offlinePage, response);
    });
  }));
});

/*
	If any fetch fails, it will show the offline page.
	Maybe this should be limited to HTML documents?
*/
self.addEventListener('fetch', function(event)
{
  event.respondWith(
    fetch(event.request).catch(function(error)
    {
        console.error( '[ServiceWorker] Network request Failed. Serving offline page. ' + error );
        return caches.open(cacheName).then(function(cache)
        {
          return cache.match('offline.html');
      });
    }));
});

/*
	This is an event that can be fired from your page to tell the SW to update the offline page
*/
self.addEventListener('refreshOffline', function(response)
{
  return caches.open(cacheName).then(function(cache)
  {
    console.log('[ServiceWorker] Offline page updated from refreshOffline event: '+ response.url);
    return cache.put(offlinePage, response);
  });
});
