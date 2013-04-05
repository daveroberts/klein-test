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
	user.$save({}, function(data){
		$scope.users.push(data);
	}, function(data){
		$scope.nameError = data.data.name;
	});
  }
  
  $scope.deleteUser = function(user) {
	if (confirm('Delete ' + user.name + "?")){
		user.$delete();
		$scope.users.remove(user, jQuery.inArray($scope.users));
	} else {
	}
  }
}