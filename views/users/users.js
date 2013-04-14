angular.module('usersApp', ['ngResource']);

function UsersCtrl($scope, $resource, $http) {
  var User = $resource('/users/:id', {id:'@id'}, { update: {method:'PUT'} });
  $scope.returnedUser = "";
  $scope.users = User.query();
  $scope.name = '';
  $scope.nameError = '';
  $scope.userToEdit = null;
  $http.get('/current_user').success(function(data){ $scope.current_user = data; });
  $scope.createUser = function() {
    $scope.nameError = '';
    if ($scope.name == ''){
      $scope.nameError = "Name cannot be blank";
      return;
    }
    var user = new User({name:$scope.name});
    user.$save({}, function(data){
      $scope.users.push(data);
      $().toastmessage('showSuccessToast', 'Added '+data.name);
      $scope.name = '';
    }, function(data){
      $scope.nameError = data.data.name;
    });
  }
  
  $scope.deleteUser = function(user) {
    bootbox.confirm('Delete ' + user.name + '?', function(result) {
      if (result){
        user.$delete(function(data){
          $.each($scope.users, function(index, u){
            if (u.id != user.id){ return true; }
            $scope.users.splice(index, 1);
            return false;
          });
          $().toastmessage('showSuccessToast', 'Removed '+user.name);
        }, function(data){
          $scope.users = User.query();
        });
      }
    });
  }
  $scope.editUser = function(user) {
    $scope.userToEdit = new User({id:user.id,name:user.name});
  }
  $scope.updateUser = function(){
    $scope.userToEdit.$update({id:$scope.userToEdit.id},function(data){
      $.each($scope.users, function(index, user){
        if (user.id != $scope.userToEdit.id){ return true; }
        $scope.users[index] = $scope.userToEdit;
        return false;
      });
      $scope.userToEdit = null;
    }, function(data){
    });
  }
  $scope.cancelEdit = function(user) {
    $scope.userToEdit = null;
  }
  $scope.login = function(){
    data = {username:$scope.username,password:$scope.password};
    $http({method: 'POST', url: '/login', data: data}).
      success(function(data, status, headers, config) {
        $scope.current_user = $scope.username;
        $().toastmessage('showSuccessToast', 'Logged in as '+$scope.username);
      }).
      error(function(data, status, headers, config) {
        bootbox.alert("Login Error");
    });
  }
  $scope.logout = function(){
    $http({method: 'POST', url: '/logout'}).
      success(function(data, status, headers, config) {
        $scope.current_user = null;
      });
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