angular.module('usersApp', ['ngResource']);

function UsersCtrl($scope, $resource, $http) {
  var User = $resource('/users/:id', {id:'@id'});
  $scope.returnedUser = "";
  $scope.users = User.query();
  $scope.getUser = function () {
    var user = User.get({id:$scope.userID}, function() {
      $scope.returnedUser = user;
    });
  }
  $scope.createUser = function() {
    var user = new User({name:$scope.name});
	user.$save({}, function(data){
		$scope.users.push(data);
	}, function(data){
		$scope.nameError = data.data.name;
	});
  }
  
  $scope.deleteUser = function(user) {
	bootbox.confirm('Delete ' + user.name + '?', function(result) {
		if (result){
			debugger;
			user.$delete({}, function(data){
				debugger; // silly change
				$scope.users = User.query();
			}, function(data){
				debugger;
			});
		} else {
		}
	});
  }
}