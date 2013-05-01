function LoginCtrl($scope, $resource, $http, AuthService) {
  $http.get('/current_user').success(function(data){ AuthService.login(data); });
  $scope.login = function(){
    data = {username:$scope.username,password:$scope.password};
    $http({method: 'POST', url: '/login', data: data}).
      success(function(data, status, headers, config) {
        AuthService.login($scope.username);
        $.gritter.add({
          title: 'Success', text: 'Logged in as ' + $scope.username,
          image: '/assets/gritter/images/success.png',
          time: 3000, class_name: 'push_below_top_bar',
        });
      }).
      error(function(data, status, headers, config) {
        $.gritter.add({
          title: 'Error', text: 'Username / password not accepted',
          image: '/assets/gritter/images/error.png',
          time: 3000, class_name: 'push_below_top_bar',
        });
        $('#password').val("");
        $('#username').select();
    });
  }
  $scope.logout = function(){
    $http({method: 'POST', url: '/logout'}).
      success(function(data, status, headers, config) {
        AuthService.logout();
      });
  }
}