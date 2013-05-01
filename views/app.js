angular.module('usersApp', ['ngResource']).
  factory( 'AuthService', function() {
    var current_user;

    return {
      login: function(user) { current_user = user; },
      logout: function() { current_user = null; },
      isLoggedIn: function() { return current_user != null; },
      current_user: function() { return current_user; }
    };
  }).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.
      when('/users', {templateUrl: '/views/users/users.html', controller: UsersCtrl}).
      when('/systems', {templateUrl: '/views/systems.html', controller: SystemsCtrl}).
      otherwise({redirectTo: '/users'});
}]);