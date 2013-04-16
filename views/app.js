angular.module('usersApp', ['ngResource']).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.
      when('/users', {templateUrl: '/views/users/users.html', controller: UsersCtrl}).
      when('/systems', {templateUrl: '/views/systems.html', controller: SystemsCtrl}).
      otherwise({redirectTo: '/users'});
}]);