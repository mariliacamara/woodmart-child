(function () {
  'use strict';

  var userAgent = navigator.userAgent || '';
  var isMac = userAgent.indexOf('Macintosh') !== -1 || userAgent.indexOf('Mac OS X') !== -1;

  if (!isMac) {
    return;
  }

  document.documentElement.classList.add('is-mac');

  if (document.body) {
    document.body.classList.add('is-mac');
    return;
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.body.classList.add('is-mac');
  });
})();
