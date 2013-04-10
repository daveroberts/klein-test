angular.module('usersApp', ['ngResource']);

function UsersCtrl($scope, $resource, $http) {
  var User = $resource('/users/:id', {id:'@id'});
  $scope.returnedUser = "";
  $scope.users = User.query();
  $scope.name = '';
  $scope.nameError = '';
  $scope.userToEdit = null;
  $scope.createUser = function() {
    $scope.nameError = '';
    if ($scope.name == ''){
      $scope.nameError = "Name cannot be blank";
      return;
    }
    var user = new User({name:$scope.name});
    user.$save({}, function(data){
      $scope.users.push(data);
      $scope.name = '';
    }, function(data){
      $scope.nameError = data.data.name;
    });
  }
  
  $scope.deleteUser = function(user) {
    bootbox.confirm('Delete ' + user.name + '?', function(result) {
      if (result){
        user.$delete(function(data){
          $scope.users = User.query();
        }, function(data){
        });
      }
    });
  }
  $scope.editUser = function(user) {
    $scope.userToEdit = user;
  }
  $scope.cancelEdit = function(user) {
    $scope.userToEdit = null;
  }
}

function RandomCtrl($scope, $timeout){
  $scope.counter = 0;
  $scope.onTimeout = function(){
      $scope.counter++;
      mytimeout = $timeout($scope.onTimeout,1000);
  }
  var mytimeout = $timeout($scope.onTimeout,1000);
}