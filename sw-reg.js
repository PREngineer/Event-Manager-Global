/*************************************************
	This is the "Offline page" service worker
*************************************************/

/** 
 * 	Add this content to your HTML page;
 * 	or add the js file to your page, at the very top,
 * 	to register the service worker
*/
if (navigator.serviceWorker.controller)
{
  console.log('[ServiceWorker] Active service worker found, no need to register.')
}
else
{
  //Register the ServiceWorker
  navigator.serviceWorker.register( 'sw.js', {scope: '.'} ).then(
    function(reg)
    {
      console.log('[ServiceWorker] Service worker has been registered for scope: '+ reg.scope);
    }
  );
}
