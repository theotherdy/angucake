/**
 * Main app modules
 */
 
// main app Dependency 
angular.module('core', ['ui.router', 'ngResource']);
angular.module('controllers', []);
angular.module('factories', []);
angular.module('configs', []);
angular.module('runs', []);
angular.module('app', ['core', 'runs', 'configs', 'factories',  'controllers']);