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
      $.gritter.add({
        title: 'Success', text: 'User added successfully',
        image: '/assets/gritter/images/success.png',
        time: 3000, class_name: 'push_below_top_bar',
      });
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
          $scope.userToEdit = null;
          $.gritter.add({
            title: 'Success', text: 'User removed successfully',
            image: '/assets/gritter/images/success.png',
            time: 3000, class_name: 'push_below_top_bar',
          });
        }, function(data){
          //$scope.users = User.query();
          $scope.userToEdit = null;
        });
      }
    });
    return false;
  }
  $scope.editUser = function(user) {
    $scope.userToEdit = new User();
    angular.copy(user, $scope.userToEdit);
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
    //if ($scope.editUserForm.$dirty) {
    $scope.userToEdit = null;
  }
}