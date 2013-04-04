angular.module('usersApp', ['ngResource']);

function UsersCtrl($scope, $resource, $http) {
  var User = $resource('/users/:id', {id:'@id'});
  $scope.returnedUser = "";
  $scope.getUser = function () {
    var user = User.get({id:$scope.userID}, function() {
      $scope.returnedUser = user;
    });
  }
  $scope.getUsers = function () {
    $http.get("/users")
      .success(function(data, status, headers, config) {
        $scope.users = data;
        $scope.status = status;
      }).error(function(data, status, headers, config) {
        $scope.status = status;
    });
  }
  $scope.createUser = function() {
    var user = new User({name:$scope.name});
    user.$save();
    $scope.users.push(user);
  }
}