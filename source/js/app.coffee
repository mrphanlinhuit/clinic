"use strict"
App = angular.module("App", [
	"ngAnimate"
	"ngSanitize"
	"ngRoute"
	"ngCookies"
	"ngResource"
	"App.filters"
	"App.services"
	"App.directives"
	"App.controllers"
	#"App.templates"
	"ui.bootstrap"
	"ui.calendar"
	"angularFileUpload"
	"ngTagsInput"
	"textAngular"
])
.constant "DOMAIN",	""
.constant "CURRENCY", "€ "
.constant "SESSION_COOKIE_NAME", "session"
.constant "DELAY", 5000
.constant "DEFAULT_ROUTE", "/dashboard"
.constant "REQUIRE_AUTH", "/user/signin"
.constant "PERPAGE", [10,20,50,100]
.constant "version", "© 2014 beesightsoft.com"
.config([
	"$routeProvider"
	"$locationProvider"
	"DEFAULT_ROUTE"
	($routeProvider, $locationProvider, DEFAULT_ROUTE) ->
		$routeProvider.when "/pdf/invoice/:id",
			templateUrl: "html/accounting/invoice.html"
		$routeProvider.when "/accounting/invoice/:id",
			templateUrl: "html/accounting/invoice.html"
		$routeProvider.when "/accounting/invoices",
			templateUrl: "html/accounting/invoices.html"
		$routeProvider.when "/accounting/download",
			templateUrl: "html/accounting/download.html"
		$routeProvider.when "/accounting/provider-new",
			templateUrl: "html/accounting/provider-new.html"
		$routeProvider.when "/accounting/provider/:id",
			templateUrl: "html/accounting/provider.html"
		$routeProvider.when "/accounting/providers",
			templateUrl: "html/accounting/providers.html"
		$routeProvider.when "/accounting/provider-list",
			templateUrl: "html/accounting/provider-list.html"
		$routeProvider.when "/accounting/provider-invoices",
			templateUrl: "html/accounting/provider-invoices.html"
		$routeProvider.when "/accounting/statistics",
			templateUrl: "html/accounting/statistics.html"

		$routeProvider.when "/administration",
			templateUrl: "html/administration/administration.html"
		$routeProvider.when "/administration/bonds",
			templateUrl: "html/administration/bonds.html"
		$routeProvider.when "/administration/clinic",
			templateUrl: "html/administration/clinic.html"
		$routeProvider.when "/administration/pathologies",
			templateUrl: "html/administration/pathologies.html"
		$routeProvider.when "/administration/referer",
			templateUrl: "html/administration/referer.html"
		$routeProvider.when "/administration/referer/:id",
			templateUrl: "html/administration/referer.html"
		$routeProvider.when "/administration/referers",
			templateUrl: "html/administration/referers.html"
		$routeProvider.when "/administration/rooms",
			templateUrl: "html/administration/rooms.html"
		$routeProvider.when "/administration/room",
			templateUrl: "html/administration/room.html"
		$routeProvider.when "/administration/room/:id",
			templateUrl: "html/administration/room.html"
		$routeProvider.when "/administration/treatments",
			templateUrl: "html/administration/treatments.html"
		$routeProvider.when "/administration/news",
			templateUrl: "html/administration/news.html"
			controller: "NewsCtrl"


		$routeProvider.when "/cash/movements",
			templateUrl: "html/cash/movements.html"
		$routeProvider.when "/cash/statistics",
			templateUrl: "html/cash/statistics.html"

		$routeProvider.when "/calendar",
			templateUrl: "html/calendar/index.html"

		$routeProvider.when DEFAULT_ROUTE,
			templateUrl: "html/dashboard.html"
			controller: "DashboardCtrl"
			resolve:resolve = user: (securityService) ->
				securityService.requestCurrentUser()

		$routeProvider.when "/user/list",
			templateUrl: "html/user/list.html"

		#$routeProvider.when "/user/signup",
		#	templateUrl: "html/user/signup.html"
		#	controller: "SignupCtrl"
		$routeProvider.when "/user/signout",
			controller: "SignoutCtrl"
			resolve:resolve = logout: ($location, securityService) ->
				securityService.destroySession()
				$location.path "/user/signin"
				return

		$routeProvider.when "/user/signin",
			templateUrl: "html/user/signin.html"
			controller: "SigninCtrl"

		$routeProvider.when "/user/password",
			templateUrl: "html/user/password.html"
			controller: "PasswordCtrl"

		$routeProvider.when "/user/lock",
			templateUrl: "html/lock.html"

		$routeProvider.when "/user/forgot",
			templateUrl: "html/user/forgot.html"

		$routeProvider.when "/messages",
			templateUrl: "html/user/messages.html"
		$routeProvider.when "/messages/:id",
			templateUrl: "html/user/message.html"

		$routeProvider.when "/user/:id",
			templateUrl: "html/user/user.html"


		$routeProvider.when "/patient/list",
			templateUrl: "html/patient/list.html"

		$routeProvider.when "/patient/invoice/:id",
			templateUrl: "html/patient/invoice.html"

		$routeProvider.when "/patient/cash/:id",
			templateUrl: "html/patient/cash.html"

		$routeProvider.when "/patient/invoices/:id",
			templateUrl: "html/patient/invoices.html"

		$routeProvider.when "/patient/timeline/:id",
			templateUrl: "html/patient/timeline.html"

		$routeProvider.when "/patient/appointments/:id",
			templateUrl: "html/patient/appointments.html"

		$routeProvider.when "/patient/bonds/:id",
			templateUrl: "html/patient/bonds.html"

		$routeProvider.when "/patient/diagnostic",
			templateUrl: "html/patient/diagnostic.html"

		$routeProvider.when "/patient/diagnostic/:id",
			templateUrl: "html/patient/diagnostic.html"

		$routeProvider.when "/patient/diagnostics/:id",
			templateUrl: "html/patient/diagnostics.html"

		$routeProvider.when "/patient/:id",
			templateUrl: "html/patient/patient.html"

		$routeProvider.otherwise redirectTo: DEFAULT_ROUTE
		$locationProvider.html5Mode false
])
.config [
	"$httpProvider"
	($httpProvider) ->

		# Set common http headers
		$httpProvider.defaults.headers.common =
			"Accept": "application/json, text/plain, */*"
			"Content-Type": "application/json;charset=utf-8"
			"X-Requested-With": "XMLHttpRequest"

		# http response interceptor
		$httpProvider.responseInterceptors.push ($q, $location, $rootScope, DEFAULT_ROUTE) ->
			(promise) ->
				promise.then ((response) ->
					# if success HTTP status code 200
					if response.headers()["content-type"] is "application/json; charset=utf-8" or response.headers()["content-type"] is "application/json"
						if response.data.code is 200 or response.data.code is 302
							payloadData = response.data.payload
							response.data = payloadData
							return response
						else
							if response.data.code is 400 # Bad Request
								$rootScope.$broadcast "error", response.data.message or "Error. Bad Request."
							else if response.data.code is 401 # Unauthorized
								#$rootScope.$broadcast('error', response.data.message || 'Error. Unauthorized');
								currentPath = $location.path()
								if currentPath isnt "/user/signout" and $location.path() isnt "/user/signin" and $location.path() isnt "/user/signup"
									ref = $location.$$url
									$location.path("/user/signout").search ref: ref
							else if response.data.code is 403 # Forbidden
								if $location.path() isnt DEFAULT_ROUTE
									$rootScope.$broadcast "error", response.data.message or "Error. Forbidden."
									#$location.path DEFAULT_ROUTE
							else if response.data.code is 404 # Not Found
								if $location.path() isnt DEFAULT_ROUTE
									$rootScope.$broadcast "error", response.data.message or "Error. Not Found."
									#$location.path DEFAULT_ROUTE
							else
								$rootScope.$broadcast "error", response.data.message or "Error. Server is having a problem"

							return $q.reject(response)
					response
				), (response) ->

					# if error (HTTP status code 4xx)
					# $rootScope.$broadcast "error", "Error. Your connection is having a problem"

					#console.log('Request error:', response);
					$q.reject response
]

.config [
	"$compileProvider"
	($compileProvider) ->
		$compileProvider.aHrefSanitizationWhitelist /^\s*(https?|mailto|tel|sms):/
]

.run [
	"$rootScope"
	"$location"
	"securityService"
	"REQUIRE_AUTH"
	($rootScope, $location, securityService, REQUIRE_AUTH) ->
		skipAuth = [
			"/user/signin"
			"/user/signout"
			"/user/lock"
		]
		$rootScope.isPath = null
		securityService.init()
		return $rootScope.$on("$routeChangeStart", (event, next, current) ->
			isAuthenticated = undefined
			query = undefined
			$rootScope.isPath = $location.path()
			isAuthenticated = securityService.isAuthenticated()
			if isAuthenticated
				query = $location.search()
				$location.search({}).path query.ref  if query.ref
			else
				$location.path REQUIRE_AUTH  unless _(skipAuth).contains($rootScope.isPath)
			return
		)
]