// window.addEventListener('message', function (e) {
//     var $iframe = jQuery("#myIframe");
//     var eventName = e.data[0];
//     var data = e.data[1];
//     switch (eventName) {
//         case 'setHeight':
//             $iframe.height(data);
//             break;
//     }
// }, false);
//
// function resize() {
//     var height = document.getElementsByTagName("html")[0].scrollHeight;
//     window.parent.postMessage(["setHeight", height], "*");
// }
// //window resize
// $( window ).resize(function() {
//     var height = document.getElementsByTagName("html")[0].scrollHeight;
//     window.parent.postMessage(["setHeight", height], "*");
// });
// $( window ).trigger("resize");