angular.module("App.controllers", [])


.controller "ProviderCtrl", [
	"$rootScope"
	"$scope"
	"$routeParams"
	"$location"
	"$fileUploader"
	"providerResource"
	"PostalData"
	"CountryData"
	($rootScope, $scope, $routeParams, $location, $fileUploader, providerResource, PostalData, CountryData) ->
		id = $routeParams.id
		if !parseInt(id)
			$scope.provider = {id:""}
			$scope.reset = angular.copy($scope.provider)
		else
			providerResource.get(id, {}).success (payload) ->
				$scope.provider = payload
				$scope.reset = angular.copy($scope.provider)
				return


		$scope.onZipcode = (zipcode) ->
			key = zipcode.substring(0,2)
			angular.forEach PostalData, (postal) ->
				if postal.key == key
					$scope.provider.city = postal.name
					return

		$scope.dismiss = ->
			angular.copy $scope.reset, $scope.provider
			return

		$scope.countries = CountryData
		$scope.postals   = PostalData

		# save
		$scope.submit = ->
			if $scope.provider.id
				providerResource.update($scope.provider.id, $scope.provider).success (payload) ->
					$rootScope.$broadcast "success", "Provider has been updated successfully!"
					$scope.provider = payload
					return

			else
				providerResource.create($scope.provider).success (payload) ->
					$rootScope.$broadcast "success", "Provider has been created successfully!"
					return
		return

]

.controller "ProvidersCtrl", [
	"$rootScope"
	"$scope"
	"$modal"
	"$location"
	"providerResource"
	"PERPAGE"
	($rootScope, $scope, $modal, $location, providerResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.order   = $location.search().order or ""
		$scope.limit   = $location.search().limit or ""
		providerResource.search($location.search()).success (payload) ->
			$scope.results = payload

		# Modal of session
		$scope.open = (id) ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/provider.html"
				controller: providerDialogController = [
					"$scope"
					"$modalInstance"
					"CountryData"
					"PostalData"
					"providerResource"
					($scope, $modalInstance, CountryData, PostalData, providerResource) ->
						$scope.countries = CountryData
						$scope.postals   = PostalData
						if !parseInt(id)
							$scope.provider = {id:""}
						else
							providerResource.get(id, {}).success (payload) ->
								$scope.provider = payload
								return

						# save
						$scope.submit = ->
							if $scope.provider.id
								providerResource.update($scope.provider.id, $scope.provider).success (payload) ->
									$rootScope.$broadcast "success", "Provider has been updated successfully!"
									$scope.provider = payload
									$modalInstance.close $scope.provider
									return

							else
								providerResource.create($scope.provider).success (payload) ->
									$rootScope.$broadcast "success", "Provider has been created successfully!"
									$modalInstance.close $scope.provider
									return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)

			modal.result.then (provider) ->
				if provider
					providerResource.search({}).success (payload) ->
						$scope.results = payload
						return
				return



		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				providerResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Provider file has been deleted successfully!"
					providerResource.search().success (payload) ->
						$scope.results = payload

		$scope.search = ->
			$location.search {q: $scope.q}

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})

		return
]


.controller "InvoiceCtrl", [
	"$rootScope"
	"$scope"
	"$routeParams"
	"invoiceResource"
	($rootScope, $scope, $routeParams, invoiceResource) ->
		id = $routeParams.id
		invoiceResource.get(id, {}).success (payload) ->
			$scope.invoice = payload
			return
		return

]

.controller "ProviderInvoicesCtrl", [
	"$rootScope"
	"$scope"
	"$http"
	"$modal"
	"$timeout"
	"$location"
	"$fileUploader"
	"$routeParams"
	"invoiceResource"
	"providerResource"
	"CountryData"
	"PostalData"
	"DOMAIN"
	"PERPAGE"
	($rootScope, $scope, $http, $modal, $timeout, $location, $fileUploader, $routeParams, invoiceResource, providerResource, CountryData, PostalData, DOMAIN, PERPAGE) ->
		$scope.countries   = CountryData
		$scope.postals     = PostalData
		$scope.perpage     = PERPAGE
		$scope.q           = $location.search().q or ""
		$scope.sort        = $location.search().sort or ""
		$scope.order       = $location.search().order or ""
		$scope.token       = $http.defaults.headers.common["Authorization"]
		$scope.dateOptions = {startingDay: 1, showWeeks: "false"}
		id                 = $routeParams.id
		$scope.invoice     = {}
		if !parseInt(id)
			$scope.provider = {id:""}
			$scope.reset = angular.copy($scope.provider)
			$scope.results = []
		else
			providerResource.get(id, {}).success (payload) ->
				$scope.provider = payload
				$scope.reset = angular.copy($scope.provider)
				invoiceResource.search({provider_id: id}).success (payload) ->
					$scope.results = payload
				return

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				sort: $scope.sort
				order: $scope.order
				q: $scope.q
			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.onZipcode = (zipcode) ->
			key = zipcode.substring(0,2)
			angular.forEach PostalData, (postal) ->
				if postal.key == key
					$scope.provider.city = postal.name
					return

		$scope.dismiss = ->
			angular.copy $scope.reset, $scope.provider
			return

		# save
		$scope.submit = ->
			if $scope.provider.id
				providerResource.update($scope.provider.id, $scope.provider).success (payload) ->
					$rootScope.$broadcast "success", "Provider has been updated successfully!"
					$scope.provider = payload
					return

			else
				providerResource.create($scope.provider).success (payload) ->
					$rootScope.$broadcast "success", "Provider has been created successfully!"
					return

		$scope.create = ->
			console.log $scope.attachment
			invoiceResource.create(_.extend($scope.invoice, {attachment:$scope.attachment, provider_id: id})).success (payload) ->
				$scope.invoice = {}
				$rootScope.$broadcast "success", "Provider Invoice has been created successfully!"
				invoiceResource.search({provider_id: id}).success (payload) ->
					$scope.results = payload
					return
			return
		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				invoiceResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Provider Invoice file has been deleted successfully!"
					invoiceResource.search().success (payload) ->
						$scope.results = payload

		# create a uploader with options
		uploader = $scope.uploader = $fileUploader.create(
			scope: $scope
			url: DOMAIN + "/api/upload" + "?pid=" + id
			headers:
				Authorization: $scope.token

			autoUpload: true
			removeAfterUpload: true
			formData: []
			filters: []
		)
		uploader.bind "progressall", (event, progress) ->
			$scope.progress = progress
			$scope.showprogress = true
			return
		uploader.bind "complete", (event, xhr, item, response) ->
			$scope.attachment = response.payload.id

			$timeout (->
				$scope.showprogress = false
				return
			), 3000
			return console.log $scope.attachment


		$scope.viewInvoice = (invoice) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/invoice.html"
				controller: viewInvoiceDialogController = [
					"$scope"
					"$modalInstance"
					($scope, $modalInstance) ->
						$scope.invoice = invoice
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
		$scope.open = ($event) ->
			$event.preventDefault()
			$event.stopPropagation()
			$scope.opened = true
			return
		return
]


.controller "ProvidersInvoicesCtrl", [
	"$rootScope"
	"$scope"
	"$modal"
	"$location"
	"invoiceResource"
	"PERPAGE"
	($rootScope, $scope, $modal, $location, invoiceResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.order   = $location.search().order or ""
		$scope.limit   = $location.search().limit or ""
		invoiceResource.search($location.search()).success (payload) ->
			$scope.results = payload

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				sort: $scope.sort
				order: $scope.order
				q: $scope.q
			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		# Modal of session
		$scope.open = (id) ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/invoice.html"
				controller: providerInvoicesDialogController = [
					"$scope"
					"$modalInstance"
					"CountryData"
					"PostalData"
					"invoiceResource"
					($scope, $modalInstance, CountryData, PostalData, invoiceResource) ->
						$scope.countries = CountryData
						$scope.postals   = PostalData
						if !parseInt(id)
							$scope.provider = {id:""}
						else
							invoiceResource.get(id, {}).success (payload) ->
								$scope.provider = payload
								return

						# save
						$scope.submit = ->
							if $scope.provider.id
								invoiceResource.update($scope.provider.id, $scope.provider).success (payload) ->
									$rootScope.$broadcast "success", "Provider has been updated successfully!"
									$scope.provider = payload
									$modalInstance.close $scope.provider
									return

							else
								invoiceResource.create($scope.provider).success (payload) ->
									$rootScope.$broadcast "success", "Provider has been created successfully!"
									$modalInstance.close $scope.provider
									return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)

			modal.result.then (provider) ->
				if provider
					invoiceResource.search({}).success (payload) ->
						$scope.results = payload
						return
				return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				invoiceResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Provider file has been deleted successfully!"
					invoiceResource.search().success (payload) ->
						$scope.results = payload
		$scope.viewInvoice = (invoice) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/invoice.html"
				controller: viewInvoiceDialogController = [
					"$scope"
					"$modalInstance"
					($scope, $modalInstance) ->
						$scope.invoice = invoice
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
		return
]



.controller "InvoicesCtrl", [
	"$rootScope"
	"$scope"
	"$modal"
	"$location"
	"invoiceResource"
	($rootScope, $scope, $modal, $location, invoiceResource) ->
		invoiceResource.search({}).success (payload) ->
			$scope.results = payload

		# Modal of session
		$scope.open = (id) ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/provider.html"
				controller: providerDialogController = [
					"$scope"
					"$modalInstance"
					"CountryData"
					"PostalData"
					"invoiceResource"
					($scope, $modalInstance, CountryData, PostalData, invoiceResource) ->
						$scope.countries = CountryData
						$scope.postals   = PostalData
						if !parseInt(id)
							$scope.provider = {id:""}
						else
							invoiceResource.get(id, {}).success (payload) ->
								$scope.provider = payload
								return

						# save
						$scope.submit = ->
							if $scope.provider.id
								invoiceResource.update($scope.provider.id, $scope.provider).success (payload) ->
									$rootScope.$broadcast "success", "Provider has been updated successfully!"
									$scope.provider = payload
									$modalInstance.close $scope.provider
									return

							else
								invoiceResource.create($scope.provider).success (payload) ->
									$rootScope.$broadcast "success", "Provider has been created successfully!"
									$modalInstance.close $scope.provider
									return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)

			modal.result.then (provider) ->
				if provider
					invoiceResource.search({}).success (payload) ->
						$scope.results = payload
						return
				return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				invoiceResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Provider file has been deleted successfully!"
					invoiceResource.search().success (payload) ->
						$scope.results = payload
		return
]


.controller('AppCtrl', [
	'$scope', '$location'
	($scope, $location) ->
		$scope.isHide = ->
			path = $location.path()
			return _.contains( [
					'/404'
					'/500'
					'/user/lock'
					'/user/signin'
					'/user/signup'
					'/user/forgot'
			], path )

		$scope.main =
			brand: 'Clinic Management'
			name: 'Admin'
])

.controller("HeaderCtrl",[
	"$scope"
	"$rootScope"
	"$routeParams"
	"$location"
	"$interval"
	"securityService"
	"messageResource"
	"userResource"
	"searchResource"
	($scope, $rootScope, $routeParams, $location, $interval, securityService, messageResource, userResource, searchResource) ->
		id = $routeParams.id
		if id is "new"
			$scope.user  = userResource.defaults
		else
			if !parseInt(id)
				id = "me"
			userResource.get(id, {}).success (payload) ->
				$scope.user = payload
				return	

		$scope.isAuthenticated = securityService.isAuthenticated()

		$scope.syncMsg = ->
			if $scope.isAuthenticated
				messageResource.search({}).success (payload) ->
					$scope.messages = payload
		# pause for dev
		$interval($scope.syncMsg(), 60000)
		$scope.syncMsg
		$scope.$on "authChange", (event) ->
			$scope.isAuthenticated = securityService.isAuthenticated()
			if $scope.isAuthenticated
				$scope.user = securityService.requestCurrentUser()

			else
				$scope.user = null
			return
		return
])

.controller('NavCtrl', [
	'$scope', 'securityService'
	($scope, securityService) ->

		$scope.isAdmin = ->
			return true  if $scope.user.role_id[0].name is "admin"  if $scope.isAuthenticated
			false
		$scope.$on "authChange", (event) ->
			$scope.isAuthenticated = securityService.isAuthenticated()
			if $scope.isAuthenticated
				$scope.user = securityService.requestCurrentUser()

			else
				$scope.user = null
			return
])

.controller "SignoutCtrl", [
	"$scope"
	($scope) ->
		return
]

.controller "LockCtrl", [
	"$scope"
	"$rootScope"
	"$location"
	"securityService"
	"authResource"
	"DEFAULT_ROUTE"
	($scope, $rootScope, $location, securityService, authResource, DEFAULT_ROUTE) ->
		$scope.user = securityService.requestCurrentUser()
		securityService.destroySession()
		ref = $location.$$url
		$scope.password = ""
		$location.path "user/signin"  unless $scope.user.credential.email

		$scope.submit = ->
			authResource.login(
					username: $scope.user.credential.email
					password: $scope.password
				).success (payload) ->
					securityService.init payload
					$rootScope.$broadcast "success", "Welcome!"

					$location.path DEFAULT_ROUTE
			return
		return
]

.controller "SigninCtrl", [
	"$scope"
	"$rootScope"
	"$location"
	"authResource"
	"securityService"
	"DEFAULT_ROUTE"
	"REQUIRE_AUTH"
	($scope, $rootScope, $location, authResource, securityService, DEFAULT_ROUTE, REQUIRE_AUTH) ->
		$scope.username = "admin@example.com"
		$scope.password = "password"
		$scope.submit = ->
			$scope.form.$setDirty()
			if $scope.form.$valid
				authResource.login(
					username: $scope.username
					password: $scope.password
				).success (payload) ->
					securityService.init payload
					$rootScope.$broadcast "success", "Welcome!"

					# last url / or default if none
					if $rootScope.isPath and $rootScope.isPath isnt REQUIRE_AUTH
						path = $rootScope.isPath
					else
						path = DEFAULT_ROUTE

					$location.path path

			return

		return
]

.controller("SignupCtrl",[
	"$scope"
	"$rootScope"
	"$location"
	"userResource"
	"authResource"
	"securityService"
	"DEFAULT_ROUTE"
	($scope, $rootScope, $location, userResource, authResource, securityService, DEFAULT_ROUTE) ->
		# $scope.user = userResource.defaults
		# $scope.submit = ->
		# 	$scope.form.$setDirty()
		# 	if $scope.form.$valid
		# 		authResource.register($scope.user).success (payload) ->
		# 			securityService.init payload
		# 			$rootScope.$broadcast "success", "Welcome!"
		# 			$location.path DEFAULT_ROUTE
		# 			return
		# 	else
		# 		$rootScope.$broadcast "danger", "Error: Form Require!"
		# 		return
		# 	return

		return

])

.controller("PasswordCtrl",[
	"$rootScope"
	"$scope"
	"$location"
	"userResource"
	"securityService"
	"REQUIRE_AUTH"
	($rootScope, $scope, $location, userResource, securityService, REQUIRE_AUTH) ->
		$scope.currentUser = securityService.requestCurrentUser()
		if securityService.isAuthenticated()
			userResource.getMe().success (payload) ->
				$scope.user = payload
				return
		else
			$rootScope.$broadcast "danger", "Error: You must login again!"
			$location.path REQUIRE_AUTH
			return

		# save
		$scope.save = ->
			$scope.form.$setDirty()
			if $scope.form.$valid
				if $scope.user.id
					userResource.updateMe($scope.user).success (payload) ->
						$rootScope.$broadcast "success", "User has been changed password successfully!"
						return
				else
					$rootScope.$broadcast "danger", "Error: User is not found!"
					return
			else
				$rootScope.$broadcast "danger", "Error: Not changed password!"
				return
			return

		return
])

.controller "CalendarsCtrl", [
	"$scope"
	"$modal"
	"$location"
	"$rootScope"
	"diagnosticResource"
	"searchResource"
	"roomResource"
	"treatmentResource"
	"sessionResource"
	"userResource"
	($scope, $modal, $location, $rootScope, diagnosticResource, searchResource, roomResource, treatmentResource, sessionResource, userResource) ->
		$scope.eventSources = $scope.events = []
		$scope.room         = $location.search().room_id or ""
		$scope.employee     = $location.search().user_id or ""
		$scope.isCollapsed  = true
		#$scope.loadingState = false

		searchResource.employee({}).success (payload) ->
			$scope.employees = payload.data
		roomResource.search({status:1}).success (payload) ->
			$scope.rooms = payload.data
		diagnosticResource.search({status:1}).success (payload) ->
			$scope.diagnostics = payload.data
		treatmentResource.search({active:1}).success (payload) ->
			$scope.treatments = payload.data
		# ID = 1 is same Profile Clinic
		userResource.get(1, {}).success (payload) ->
			$scope.clinic = payload.clinic

		$scope.goTo = (query) ->
			$scope.loadingState = true
			$location.search _.extend($location.search(),query)

		$scope.overlay = $(".fc-overlay")

		$scope.alertOnEventClick = (event, jsEvent, view) ->
			console.log event
			return


		# alert on Drop
		$scope.alertOnDrop = (event, delta, revertFunc, jsEvent, ui, view) ->
			console.log ("Event Droped to make dayDelta " + delta)
			# sessionResource.get(event.id).success (payload) ->
			# 	$scope.session = payload
			# 	if $scope.session.id
			# 		$scope.session.scheduled_at = event.start
			# 		# $scope.session.scheduled_end = event.end
			# 		sessionResource.update($scope.session.id, $scope.session).success (payload) ->
			# 			$rootScope.$broadcast "success", "Session has been updated successfully!"
			return


		# alert on Resize
		$scope.alertOnResize = (event, delta, revertFunc, jsEvent, ui, view) ->
			sessionResource.get(event.id).success (payload) ->
				$scope.session = payload
				if $scope.session.id
					$scope.session.scheduled_at = event.start
					# $scope.session.scheduled_end = event.end
					sessionResource.update($scope.session.id, $scope.session).success (payload) ->
						$rootScope.$broadcast "success", "Session has been updated successfully!"
			return
		$scope.alertOnMouseOver = (event, jsEvent, view) ->
			$scope.event = event
			$scope.overlay.removeClass("left right").find(".arrow").removeClass "left right top pull-up"
			wrap = $(jsEvent.target).closest(".fc-event")
			cal = wrap.closest(".calendar")
			left = wrap.offset().left - cal.offset().left
			right = cal.width() - (wrap.offset().left - cal.offset().left + wrap.width())
			if right > $scope.overlay.width()
				$scope.overlay.addClass("left").find(".arrow").addClass "left pull-up"
			else if left > $scope.overlay.width()
				$scope.overlay.addClass("right").find(".arrow").addClass "right pull-up"
			else
				$scope.overlay.find(".arrow").addClass "top"
			(wrap.find(".fc-overlay").length is 0) and wrap.append($scope.overlay)
			return


		# config object
		$scope.uiConfig = calendar:
			firstDay: 1
			height: 450
			editable: true
			header:
				left: "prev"
				center: "title"
				right: "next"
			disableDragging: true
			eventClick: $scope.alertOnEventClick
			eventDrop: $scope.alertOnDrop
			eventResize: $scope.alertOnResize
			eventMouseover: $scope.alertOnMouseOver


		# Change View
		$scope.changeView = (view, calendar) ->
			calendar.fullCalendar "changeView", view
			return

		$scope.today = (calendar) ->
			calendar.fullCalendar "today"
			return

		$scope.renderCalender = (calendar) ->
			calendar.fullCalendar "render"  if calendar
			return


		# Modal of session
		$scope.new = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/schedule.html"
				controller: scheduleDialogController = [
					"$scope"
					"$modalInstance"
					"searchResource"
					"sessionResource"
					"diagnosticResource"
					($scope, $modalInstance, searchResource, sessionResource, diagnosticResource) ->
						$scope.session     = {id:""}
						$scope.rooms       = $parentScope.rooms
						$scope.treatments  = $parentScope.treatments
						$scope.diagnostics = $parentScope.diagnostics
						$scope.mindate     = new Date()
						$scope.dateOptions =
							startingDay: 1
							showWeeks: "false"
						$scope.getPatient = (val) ->
							searchResource.users({'role': "user", q: val}).then (payload) ->
								patients = []
								angular.forEach payload.data.data, (item) ->
									patients.push {id:item.id, fullname: item.first_name + ' ' + item.last_name}
									return
								return patients

						$scope.open = ($event) ->
							$event.preventDefault()
							$event.stopPropagation()
							$scope.opened = true
							return

						$scope.loadEmployees = (treatment) ->
							$scope.loadingEmployees = true
							$scope.employees = []
							searchResource.users({'role': treatment.role}).success (payload) ->
								$scope.employees = payload.data
								$scope.loadingEmployees = false
								return

						$scope.$watch "session.patient", (patient)->
							if patient and patient.id
								$scope.loadDiagnostics(patient.id)

						$scope.loadDiagnostics = (patient_id) ->
							diagnosticResource.search({uid: patient_id}).success (payload) ->
								$scope.diagnostics = payload.data || []

						# save
						$scope.submit = ->
							if $scope.session.id
								sessionResource.update($scope.session.id, $scope.session).success (payload) ->
									$rootScope.$broadcast "success", "Schedule has been updated successfully!"
									$modalInstance.close payload
									return
								return
							else
								sessionResource.create(_.extend($scope.session,{})).success (payload) ->
									$rootScope.$broadcast "success", "Schedule has been created successfully!"
									$modalInstance.close payload
									return
								return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (session) ->
				if session
					# $scope.loadSchedule()
					$location.search _.extend($location.search(),
						refresh: (new Date()).getTime()
					)
					return
				return


		$scope.loadSchedule = ->
			events = []
			sessionResource.search($location.search()).success (payload) ->
				sessions = payload.data
				randomClass = [
					"b-primary"
					"b-info"
					"b-success"
					"b-danger"
					"b-warning"
				]

				angular.forEach sessions, (value) ->
					random = Math.floor(Math.random() * randomClass.length)
					events.push {
						id: value.id
						title: value.patient
						start: value.scheduled_at
						end: value.scheduled_end
						location: value.room
						notes:	value.notes
						treatment: value.treatment
						diagnostic: value.diagnostic
						author: value.author
						patient: value.patient
						phone: value.phone
						className: ['b-l b-2x ' + randomClass[random]]
					}
			$scope.eventSources = [events]
			return
		$scope.loadSchedule()
		return
]

.controller "TimelineCtrl", [
	"$scope"
	"$location"
	"$routeParams"
	"timelineResource"
	"PERPAGE"
	($scope, $location, $routeParams, timelineResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.sort    = $location.search().sort or ""
		$scope.order   = $location.search().order or ""
		$scope.limit   = $location.search().limit or ""

		id = $routeParams.id
		timelineResource.search({user_id:id}).success (payload) ->
			$scope.results = payload

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				sort: $scope.sort
				order: $scope.order
			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

]

.controller "CashCtrl", [
	"$scope"
	"$rootScope"
	"$modal"
	"$location"
	"$routeParams"
	"movementsResource"
	"PERPAGE"
	($scope, $rootScope, $modal, $location, $routeParams, movementsResource, PERPAGE) ->
		id = $routeParams.id
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.limit   = $location.search().limit or ""
		$scope.order   = $location.search().order or ""
		$scope.status  = $location.search().status or ""
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				sort: $scope.sort
				order: $scope.order
				q: $scope.q
			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		movementsResource.search(_.extend($location.search(),{user_id:id})).success (payload) ->
			$scope.results = payload

		$scope.dateOptions =
			startingDay: 1
			showWeeks: "false"

		$scope.open = ($event) ->
			$event.preventDefault()
			$event.stopPropagation()
			$scope.opened = true
			return

		$scope.choiceMovements = (movements) ->
			$scope.movements = movements

		$scope.chargebond = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/chargebond.html"
				controller: spendingDialogController = [
					"$scope"
					"$modalInstance"
					"movementsResource"
					"bondResource"
					($scope, $modalInstance, movementsResource, bondResource) ->
						$scope.movements = $parentScope.movements
						$scope.charge = {id:""}

						$scope.bond = {id:""}
						bondResource.search(_.extend($location.search(),{user_id: id})).success (payload) ->
							$scope.bonds = payload.data
							return

						$scope.submit = ->
							movementsResource.chargebond(_.extend($scope.charge, {movements:$scope.movements})).success (payload) ->
								$rootScope.$broadcast "success", "Spending has been created successfully!"
								$modalInstance.close payload
							return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (spending) ->
				if spending
					movementsResource.search(_.extend($location.search(),{user_id:id})).success (payload) ->
						$scope.results = payload
						return
				return
		$scope.charge = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/charge.html"
				controller: spendingDialogController = [
					"$scope"
					"$modalInstance"
					"movementsResource"
					($scope, $modalInstance, movementsResource) ->
						$scope.movements = $parentScope.movements
						$scope.charge = {id:""}
						$scope.submit = ->
							movementsResource.charge(_.extend($scope.charge, {movements:$scope.movements})).success (payload) ->
								$rootScope.$broadcast "success", "Spending has been created successfully!"
								$modalInstance.close payload
							return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (charge) ->
				if charge
					movementsResource.search(_.extend($location.search(),{user_id:id})).success (payload) ->
						$scope.results = payload
						return
				return
		# Modal of session
		$scope.devolution = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/devolution.html"
				controller: devolutionDialogController = [
					"$scope"
					"$modalInstance"
					"movementsResource"
					($scope, $modalInstance, movementsResource) ->
						$scope.movements = $parentScope.movements
						$scope.devolution = {id:""}
						$scope.submit = ->
							movementsResource.devolution(_.extend($scope.devolution, {movements:$scope.movements})).success (payload) ->
								$rootScope.$broadcast "success", "Devolution has been created successfully!"
								$modalInstance.close payload

							return

						$scope.dismiss = ->
							$modalInstance.close()

						return
				]
			)
			modal.result.then (devolution) ->
				if devolution
					movementsResource.search(_.extend($location.search(),{user_id:id})).success (payload) ->
						$scope.results = payload
				return
		return
]

.controller "CashStatisticsCtrl", [
	"$scope"
	"$location"
	"movementsResource"
	($scope, $location, movementsResource) ->
		$scope.today = new Date()
		$scope.end    = $location.search().end or ""
		$scope.begin   = $location.search().begin or ""
		$scope.dateOptions =
			startingDay: 1
			showWeeks: "false"

		$scope.open = ($event) ->
			$event.preventDefault()
			$event.stopPropagation()
			$scope.opened = true
			return

		$scope.dateFilter = ->
			if $scope.end and $scope.begin
				if $scope.begin > $scope.end
					$location.search({begin:$scope.end, end: $scope.begin})
				else
					$location.search({begin:$scope.begin, end: $scope.end})
			return
		$scope.dateLength = ->
			end = new Date($scope.end)
			begin = new Date($scope.begin)
			end.getDate() - begin.getDate()
		
		$scope.data1 = $scope.data2 = $scope.data3 = []
		card = money = 0
		##
		#
		##
		lineChart1 = {}
		lineChart1.data1 = [[1,15],[2,20],[3,14],[4,10],[5,10],[6,20],[7,28],[8,26],[9,22],[10,23],[11,24]]
		lineChart1.data2 = [[1,9],[2,15],[3,17],[4,21],[5,16],[6,15],[7,13],[8,15],[9,29],[10,21],[11,29]]
		$scope.lineChart = [
			data: lineChart1.data1
			label: 'Positive cash'
		,
			data: lineChart1.data2
			label: 'Negative cash'
			lines:
				fill: false
		,
			data: lineChart1.data2
			label: 'Positive - Negative cash'
			lines:
				fill: false
		]

		# Donut Chart
		card = money = bond = positive = negative = 0
		$scope.donutChart = $scope.lineChart = []
		movementsResource.search($location.search()).success (payload) ->
			length = $scope.dateLength()
			for i in length by -1
				$scope.data1.push [i,i]
			$scope.results = results = payload
			angular.forEach results.data, (value) ->
				card += parseFloat(value.amount)  if value.payment_type == 'card'
				bond += parseFloat(value.amount)  if value.payment_type == 'bond'
				money += parseFloat(value.amount)  if value.payment_type == 'money'
				positive += parseFloat(value.amount)  if value.amount > 0
				negative += parseFloat(value.amount)  if value.amount < 0
				
				return
			$scope.lineChart = [
				data: positive
				label: 'Positive cash'
			,
				data: Math.abs(negative)
				label: 'Negative cash'
			,
				data: Math.abs(negative + positive)
				label: 'Positive - Negative'
			]
			$scope.donutChart = [
				label: " Positivie Card"
				data: Math.abs(card)
			,
				label: " Positivie Cash"
				data: Math.abs(money)
			]
		return
]

.controller "MovementsCtrl", [
	"$scope"
	"$rootScope"
	"$modal"
	"$location"
	"movementsResource"
	"PERPAGE"
	($scope, $rootScope, $modal, $location, movementsResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.limit   = $location.search().limit or ""
		$scope.order   = $location.search().order or ""
		$scope.status  = $location.search().status or ""
		$scope.date = $location.search().date or ""
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				sort: $scope.sort
				order: $scope.order
				q: $scope.q
			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.dateFilter = ->
			$location.search _.extend($location.search(),{date: $scope.date})
			return

		movementsResource.search($location.search()).success (payload) ->
			$scope.results = payload

		$scope.dateOptions =
			startingDay: 1
			showWeeks: "false"

		$scope.open = ($event) ->
			$event.preventDefault()
			$event.stopPropagation()
			$scope.opened = true
			return

		$scope.choiceMovements = (movements) ->
			$scope.movements = movements

		$scope.spending = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/spending.html"
				controller: spendingDialogController = [
					"$scope"
					"$modalInstance"
					"movementsResource"
					($scope, $modalInstance, movementsResource) ->
						$scope.movements = $parentScope.movements
						$scope.spending = {id:""}
						$scope.submit = ->
							movementsResource.spending(_.extend($scope.spending, {movements:$scope.movements})).success (payload) ->
								$rootScope.$broadcast "success", "Spending has been created successfully!"
								$modalInstance.close payload
							return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (spending) ->
				if spending
					movementsResource.search($location.search()).success (payload) ->
						$scope.results = payload
						return
				return
		# Modal of session
		$scope.devolution = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/devolution.html"
				controller: devolutionDialogController = [
					"$scope"
					"$modalInstance"
					"movementsResource"
					($scope, $modalInstance, movementsResource) ->
						$scope.movements = $parentScope.movements
						$scope.devolution = {id:"", amount:$parentScope.movements.amount || "", payment_type: $parentScope.movements.payment_type || ""}
						$scope.submit = ->
							movementsResource.devolution(_.extend($scope.devolution, {movements:$parentScope.movements})).success (payload) ->
								$rootScope.$broadcast "success", "Devolution has been created successfully!"
								$modalInstance.close payload

							return

						$scope.dismiss = ->
							$modalInstance.close()

						return
				]
			)
			modal.result.then (devolution) ->
				if devolution
					movementsResource.search($location.search()).success (payload) ->
						$scope.results = payload
				return
		$scope.viewInvoice = (invoice) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/invoice.html"
				controller: viewInvoiceDialogController = [
					"$scope"
					"$modalInstance"
					($scope, $modalInstance) ->
						$scope.invoice = invoice
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
		return
]

.controller "AppointmentsCtrl", [
	"$scope"
	"$rootScope"
	"$modal"
	"$routeParams"
	"prescriptionResource"
	"roomResource"
	"sessionResource"
	"userResource"
	($scope, $rootScope, $modal, $routeParams, prescriptionResource, roomResource, sessionResource, userResource) ->
		id = $routeParams.id
		prescriptionResource.search({user_id:id}).success (payload) ->
			$scope.prescriptions = payload.data
		roomResource.search({status:1}).success (payload) ->
			$scope.rooms = payload.data
		userResource.get(id, {}).success (payload) ->
			$scope.user = payload
		return
]

.controller "DiagnosticCtrl", [
	"$rootScope"
	"$modal"
	"$scope"
	"$location"
	"$routeParams"
	"roomResource"
	"prescriptionResource"
	"pathologyResource"
	"refererResource"
	"searchResource"
	"diagnosticResource"
	"reviewResource"
	"treatmentResource"
	($rootScope, $modal, $scope, $location, $routeParams, roomResource, prescriptionResource, pathologyResource, refererResource, searchResource, diagnosticResource, reviewResource, treatmentResource) ->
		id = $routeParams.id
		$scope.showlist = []
		if parseInt(id)
			diagnosticResource.get(id).success (payload) ->
				$scope.diagnostic = payload
				$scope.reset = angular.copy($scope.diagnostic)
				return
		else
			$scope.diagnostic = {name:""}
			$scope.reset = angular.copy($scope.diagnostic)
		roomResource.search({status:1}).success (payload) ->
			$scope.rooms = payload.data
		searchResource.users({'role': "doctor"}).success (payload) ->
			$scope.doctors = payload.data
		pathologyResource.search({}).success (payload) ->
			$scope.pathologies = payload.data
		refererResource.search({status:1}).success (payload) ->
			$scope.referers = payload.data

		treatmentResource.search({active:1}).success (payload) ->
			$scope.treatments = payload.data

		$scope.refesh = ->
			$scope.showlist = []
			prescriptionResource.search({diagnostic_id:id}).success (payload) ->
				$scope.prescriptions = payload.data
				angular.forEach $scope.prescriptions, (obj) ->
					$scope.showlist.push obj
				_.sortBy $scope.showlist, (o) ->
					o.created_at
			reviewResource.search({diagnostic_id:id}).success (payload) ->
				$scope.reviews = payload.data
				angular.forEach $scope.reviews, (obj) ->
					$scope.showlist.push obj
				_.sortBy $scope.showlist, (o) ->
					o.created_at
			return
		$scope.addReview = (id) ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/addreview.html"
				controller: addreviewDialogController = [
					"$scope"
					"$modalInstance"
					"reviewResource"
					($scope, $modalInstance, reviewResource) ->
						$scope.diagnostic = angular.copy($parentScope.diagnostic)
						if parseInt(id)
							reviewResource.get(id).success (payload) ->
								$scope.review = payload
								return
						else
							$scope.review = {}
						# save
						$scope.submit = ->
							if $scope.review.id
								reviewResource.update($scope.review.id, $scope.review).success (payload) ->
									$rootScope.$broadcast "success", "Diagnostic Review has been updated successfully!"
									$modalInstance.close payload
									return
								return
							else
								reviewResource.create(_.extend($scope.review,{diagnostic_id:$scope.diagnostic.id})).success (payload) ->
									$rootScope.$broadcast "success", "Diagnostic Review has been created successfully!"
									$modalInstance.close payload
									return
								return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (review) ->
				if review
					$scope.refesh()
					return
				return

		$scope.deleteReview = (index) ->
			x = confirm "Are you sure you want to delete Review?"
			if x
				reviewResource.delete($scope.showlist[index].id).success (payload) ->
					$rootScope.$broadcast "success", "Review has been deleted successfully!"
					$scope.showlist.splice index, 1
			return

		$scope.addPrescription = (id) ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/addprescription.html"
				controller: addPrescriptionDialogController = [
					"$scope"
					"$modalInstance"
					"prescriptionResource"
					($scope, $modalInstance, prescriptionResource) ->
						$scope.diagnostic = angular.copy($parentScope.diagnostic)
						$scope.treatments = angular.copy($parentScope.treatments)
						$scope.rooms = angular.copy($parentScope.rooms)
						if parseInt(id)
							prescriptionResource.get(id).success (payload) ->
								$scope.prescription = payload
								return
						else
							$scope.prescription = {}

						# save
						$scope.submit = ->
							if $scope.prescription.id
								prescriptionResource.update($scope.prescription.id, $scope.prescription).success (payload) ->
									$rootScope.$broadcast "success", "Prescription has been updated successfully!"
									$modalInstance.close payload
									return
								return
							else
								prescriptionResource.create(_.extend($scope.prescription,{diagnostic_id:$scope.diagnostic.id})).success (payload) ->
									$rootScope.$broadcast "success", "Prescription has been created successfully!"
									$modalInstance.close payload
									return
								return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (prescription) ->
				if prescription
					$scope.refesh()
					return
				return
		$scope.deletePrescription = (index) ->
			x = confirm "Are you sure you want to delete prescription?"
			if x
				prescriptionResource.delete($scope.showlist[index].id).success (payload) ->
					$rootScope.$broadcast "success", "Prescription has been deleted successfully!"
					$scope.showlist.splice index, 1
			return

		$scope.closediagnostic = (id) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/closediagnostic.html"
				controller: closediagnosticDialogController = [
					"$scope"
					"$modalInstance"
					"diagnosticResource"
					($scope, $modalInstance, diagnosticResource) ->
						$scope.statuses = [
							"Resolved"
							"Abandoned by patient"
							"Abandoned by doctor"
						]
						if parseInt(id)
							diagnosticResource.get(id).success (payload) ->
								$scope.diagnostic = payload
								return
						else
							$scope.diagnostic = {name:""}
						# save
						$scope.submit = ->
							#$scope.form.$setDirty()
							#if $scope.form.$valid
							if $scope.diagnostic.id
								diagnosticResource.update($scope.diagnostic.id, $scope.diagnostic).success (payload) ->
									$rootScope.$broadcast "success", "Diagnostic has been updated successfully!"
									$modalInstance.close()
									return
								return
							else
								diagnosticResource.create($scope.diagnostic).success (payload) ->
									$rootScope.$broadcast "success", "Diagnostic has been created successfully!"
									$modalInstance.close()
									return
								return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then ->
				return
			return

		$scope.loadTags = (val) ->
			tagsResource.search({q: val}).then (payload) ->
				tags = []
				angular.forEach payload.data.data, (item) ->
					tags.push {id:item.id, name: item.name}
					return
				return tags

		# save and dissmis
		$scope.dismiss = ->
			angular.copy $scope.reset, $scope.diagnostic
			return

		$scope.submit = ->
			$scope.form.$setDirty()
			if $scope.form.$valid
				if $scope.diagnostic.id
					diagnosticResource.update($scope.diagnostic.id, $scope.diagnostic).success (payload) ->
						$rootScope.$broadcast "success", "Diagnostic has been updated successfully!"
						$scope.diagnostic = payload
						return
					return
				else
					diagnosticResource.create($scope.diagnostic).success (payload) ->
						$rootScope.$broadcast "success", "Diagnostic has been created successfully!"
						return
					return

		$scope.refesh()
		return
]

.controller "DiagnosticsCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"$routeParams"
	"diagnosticResource"
	"PERPAGE"
	($rootScope, $scope, $location, $modal, $routeParams, diagnosticResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.limit   = $location.search().limit or ""
		$scope.order   = $location.search().order or ""
		$scope.status  = $location.search().status or ""
		user_id        = $routeParams.id
		if parseInt(user_id)
			$scope.user_id = user_id
		diagnosticResource.search(_.extend($location.search(),{uid: user_id})).success (payload) ->
			$scope.results = payload
		$scope.openDiagnostic = (id) ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/diagnostic.html"
				controller: diagnosticDialogController = [
					"$scope"
					"$modalInstance"
					"pathologyResource"
					"refererResource"
					"searchResource"
					"diagnosticResource"
					"tagsResource"
					($scope, $modalInstance, pathologyResource, refererResource, searchResource, diagnosticResource, tagsResource) ->
						uid = $parentScope.user_id
						searchResource.users({'role': "doctor"}).success (payload) ->
							$scope.doctors = payload.data
						pathologyResource.search({}).success (payload) ->
							$scope.pathologies = payload.data
						refererResource.search({status:1}).success (payload) ->
							$scope.referers = payload.data

						if !parseInt(id)
							$scope.diagnostic = {name:"", user_id: uid}
						else
							diagnosticResource.get(id).success (payload) ->
								$scope.diagnostic = payload
								return

						$scope.loadTags = (val) ->
							tagsResource.search({q: val}).then (payload) ->
								tags = []
								angular.forEach payload.data.data, (item) ->
									tags.push {id:item.id, name: item.name}
									return
								return tags

						# save
						$scope.submit = ->
							#$scope.form.$setDirty()
							#if $scope.form.$valid
							if $scope.diagnostic.id
								diagnosticResource.update($scope.diagnostic.id, $scope.diagnostic).success (payload) ->
									$rootScope.$broadcast "success", "Diagnostic has been updated successfully!"
									$modalInstance.close()
									return
								return
							else
								diagnosticResource.create(_.extend($scope.diagnostic,{"user_id":uid})).success (payload) ->
									$rootScope.$broadcast "success", "Diagnostic has been created successfully!"
									$modalInstance.close()
									return
								return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then ->
				diagnosticResource.search($location.search()).success (payload) ->
					$scope.results = payload
					return
				return
			return

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				sort: $scope.sort
				order: $scope.order
				q: $scope.q
			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				diagnosticResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Diagnostic has been deleted successfully!"
					diagnosticResource.search($location.search()).success (payload) ->
						$scope.results = payload
						return
			return

		return
]


.controller("UserCtrl",[
	"$rootScope"
	"$scope"
	"$routeParams"
	"$http"
	"$q"
	"$timeout"
	"$modal"
	"$location"
	"$fileUploader"
	"GenderData"
	"StatusData"
	"PostalData"
	"CountryData"
	"userResource"
	"roleResource"
	"groupResource"
	"refererResource"
	"tagsResource"
	"securityService"
	"DOMAIN"
	($rootScope, $scope, $routeParams, $http, $q, $timeout, $modal, $location, $fileUploader, GenderData, StatusData, PostalData, CountryData, userResource, roleResource, groupResource, refererResource, tagsResource, securityService, DOMAIN) ->
		id = $routeParams.id
		if id is "new"
			$scope.user  = userResource.defaults
			$scope.reset = angular.copy($scope.user)
		else
			if !parseInt(id)
				id = "me"
			userResource.get(id, {}).success (payload) ->
				$scope.user = payload
				$scope.reset = angular.copy($scope.user)
				return
		refererResource.search({status:1}).success (payload) ->
			$scope.referers = payload.data

		$scope.onZipcode = (zipcode) ->
			key = zipcode.substring(0,2)
			angular.forEach PostalData, (postal) ->
				if postal.key == key
					$scope.user.meta.city = postal.name
					return
	
		$scope.loadTags = (val) ->
			tagsResource.search({q: val}).then (payload) ->
				tags = []
				angular.forEach payload.data.data, (item) ->
					tags.push {id:item.id, name: item.name}
					return
				return tags
				
		$scope.dismiss = ->
			angular.copy $scope.reset, $scope.user
			return

		$scope.open = ($event) ->
			$event.preventDefault()
			$event.stopPropagation()
			$scope.opened = true
			return

		$scope.dateOptions =
			startingDay: 1
			showWeeks: "false"

		$scope.token       = $http.defaults.headers.common["Authorization"]
		$scope.countries   = CountryData
		$scope.postals     = PostalData
		$scope.statuses    = StatusData
		$scope.genders     = GenderData
		$scope.currentUser = securityService.requestCurrentUser()

		groupResource.all().success (payload) ->
			$scope.groups = payload

		roleResource.all().success (payload) ->
			$scope.roles = payload

		$scope.toggleRole = (obj) ->
			idx = $scope.user.role_id.indexOf(obj)
			# is currently selected
			if idx > -1
				$scope.user.role_id.splice idx, 1
			# is newly selected
			else
				$scope.user.role_id.push obj
			return

		$scope.roleCheck = (obj) ->
			i = 0
			if $scope.user.roles
				while i < $scope.user.roles.length
					return true  if $scope.user.roles[i].name is obj.name
					i++
				return

		# save
		$scope.save = ->
			return  if $scope.readOnly
			$scope.form.$setDirty()
			if $scope.form.$valid
				if $scope.user.id
					if id is "me"
						userResource.updateMe($scope.user).success (payload) ->
							$rootScope.$broadcast "success", "User has been updated successfully!"
							$scope.user = payload
							return

					else
						userResource.update($scope.user.id, $scope.user).success (payload) ->
							$rootScope.$broadcast "success", "User has been updated successfully!"
							$scope.user = payload
							return

				else
					userResource.create($scope.user).success (payload) ->
						$rootScope.$broadcast "success", "User has been created successfully!"
						return

			return
		$scope.removeAvatar = ->
			$scope.user.meta.profile_img_url = ""
		uploader = $scope.uploader = $fileUploader.create(
			scope: $scope
			url: DOMAIN + "/api/upload"
			headers:
				Authorization: $scope.token

			autoUpload: true
			removeAfterUpload: true
			formData: []
			filters: []
		)

		# Images only
		uploader.filters.push (item) ->
			type = (if uploader.isHTML5 then item.type else "/" + item.value.slice(item.value.lastIndexOf(".") + 1))
			type = "|" + type.toLowerCase().slice(type.lastIndexOf("/") + 1) + "|"
			"|jpg|png|jpeg|bmp|gif|".indexOf(type) isnt -1
		uploader.bind "progressall", (event, progress) ->
			$scope.progress = progress
			$scope.showprogress = true
			return
		uploader.bind "complete", (event, xhr, item, response) ->
			$scope.user.meta.profile_img_url = response.payload.url
			$timeout (->
				$scope.showprogress = false
				return
			), 3000
			return

		$scope.avatarModal = ->
			return  if $scope.readOnly
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/user/user-avatar.html"
				controller: userDialogController = [
					"$scope"
					"$modalInstance"
					"$timeout"
					"DOMAIN"
					($scope, $modalInstance, $timeout, DOMAIN) ->
						$scope.user = angular.copy($parentScope.user)
						$scope.token = $parentScope.token
						uploader = $scope.uploader = $fileUploader.create(
							scope: $scope
							url: DOMAIN + "/api/upload"
							headers:
								Authorization: $scope.token

							autoUpload: true
							removeAfterUpload: true
							formData: []
							filters: []
						)
						# Images only
						uploader.filters.push (item) ->
							type = (if uploader.isHTML5 then item.type else "/" + item.value.slice(item.value.lastIndexOf(".") + 1))
							type = "|" + type.toLowerCase().slice(type.lastIndexOf("/") + 1) + "|"
							"|jpg|png|jpeg|bmp|gif|".indexOf(type) isnt -1

						uploader.bind "progressall", (event, progress) ->
							$scope.progress = progress
							$scope.showprogress = true
							return
						uploader.bind "complete", (event, xhr, item, response) ->
							$scope.user.meta.profile_img_url = response.payload.url
							$timeout (->
								$scope.showprogress = false
								return
							), 3000
							return
						# create a uploader with options
						$scope.save = (user) ->
							$modalInstance.close user
							return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (user) ->
				if user
					$scope.user = user
					$scope.save()
				return

			return
		return
])


.controller "AttachmentDiagnosticCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$timeout"
	"$routeParams"
	"$fileUploader"
	"$http"
	"$modal"
	"attachmentsResource"
	"tagsResource"
	"DOMAIN"
	($rootScope, $scope, $location, $timeout, $routeParams, $fileUploader, $http, $modal, attachmentsResource, tagsResource, DOMAIN) ->
		$scope.uid   = $routeParams.id
		$scope.q     = $location.search().q or ""
		$scope.sort  = $location.search().sort or ""
		$scope.order = $location.search().order or ""
		$scope.token = $http.defaults.headers.common["Authorization"]
		attachmentsResource.search({"uid": $scope.uid}).success (payload) ->
			$scope.results = payload

		# create a uploader with options
		uploader = $scope.uploader = $fileUploader.create(
			scope: $scope
			url: DOMAIN + "/api/upload" + "?uid=" + $scope.uid
			headers:
				Authorization: $scope.token

			autoUpload: true
			removeAfterUpload: true
			formData: []
			filters: []
			uid: $scope.uid
		)
		uploader.bind "progressall", (event, progress) ->
			$scope.progress = progress
			$scope.showprogress = true
			return
		uploader.bind "complete", (event, xhr, item, response) ->
			attachmentsResource.search({"uid": $scope.uid}).success (payload) ->
				$scope.results = payload
			$timeout (->
				$scope.showprogress = false
				return
			), 3000
			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				attachmentsResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Attachment file has been deleted successfully!"
					attachmentsResource.search({"uid": $scope.uid}).success (payload) ->
						$scope.results = payload
			return

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				sort: $scope.sort
				order: $scope.order
				q: $scope.q
			return

		$scope.edit = (id) ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/file.html"
				controller: attachmentsDialogController = [
					"$scope"
					"$modalInstance"
					"attachmentsResource"
					($scope, $modalInstance, attachmentsResource) ->
						$scope.attachment = {id:""}
						attachmentsResource.get(id, {}).success (payload) ->
							$scope.attachment = payload
						# save
						$scope.submit = ->
							if $scope.attachment.id
								attachmentsResource.update($scope.attachment.id, $scope.attachment).success (payload) ->
									$rootScope.$broadcast "success", "Attachment has been updated successfully!"
									$modalInstance.close payload
									return
								return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (attachment) ->
				if attachment
					attachmentsResource.search({"uid": $scope.uid}).success (payload) ->
						$scope.results = payload
						return
					return
		return
]

.controller "AttachmentCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$timeout"
	"$routeParams"
	"$fileUploader"
	"$http"
	"$modal"
	"attachmentsResource"
	"DOMAIN"
	($rootScope, $scope, $location, $timeout, $routeParams, $fileUploader, $http, $modal, attachmentsResource, DOMAIN) ->
		$scope.uid   = $routeParams.id
		$scope.q     = $location.search().q or ""
		$scope.sort  = $location.search().sort or ""
		$scope.order = $location.search().order or ""
		$scope.token = $http.defaults.headers.common["Authorization"]
		attachmentsResource.search({"uid": $scope.uid}).success (payload) ->
			$scope.results = payload

		# create a uploader with options
		uploader = $scope.uploader = $fileUploader.create(
			scope: $scope
			url: DOMAIN + "/api/upload" + "?uid=" + $scope.uid
			headers:
				Authorization: $scope.token

			autoUpload: true
			removeAfterUpload: true
			formData: []
			filters: []
			uid: $scope.uid
		)
		uploader.bind "progressall", (event, progress) ->
			$scope.progress = progress
			$scope.showprogress = true
			return
		uploader.bind "complete", (event, xhr, item, response) ->
			attachmentsResource.search({"uid": $scope.uid}).success (payload) ->
				$scope.results = payload

			$timeout (->
				$scope.showprogress = false
				return
			), 3000
			#$scope.user.meta.profile_img_url = response.payload.url
			return
		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				attachmentsResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Attachment file has been deleted successfully!"
					attachmentsResource.search({"uid": $scope.uid}).success (payload) ->
						$scope.results = payload
			return
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				sort: $scope.sort
				order: $scope.order
				q: $scope.q
			return
		$scope.edit = (id) ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/file.html"
				controller: attachmentsDialogController = [
					"$scope"
					"$modalInstance"
					"attachmentsResource"
					($scope, $modalInstance, attachmentsResource) ->
						$scope.attachment = {id:""}
						attachmentsResource.get(id, {}).success (payload) ->
							$scope.attachment = payload
						# save
						$scope.submit = ->
							if $scope.attachment.id
								attachmentsResource.update($scope.attachment.id, $scope.attachment).success (payload) ->
									$rootScope.$broadcast "success", "Attachment has been updated successfully!"
									$modalInstance.close payload
									return
								return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (attachment) ->
				if attachment
					attachmentsResource.search({"uid": $scope.uid}).success (payload) ->
						$scope.results = payload
						return
					return

		return
]

.controller "PatientsCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"searchResource"
	"userResource"
	"PERPAGE"
	($rootScope, $scope, $location, searchResource, userResource, PERPAGE) ->
		$scope.q         = $location.search().q or ""
		$scope.status    = $location.search().status or ""
		$scope.sort      = $location.search().sort or ""
		$scope.order     = $location.search().order or ""
		$scope.treatment = $location.search().treatment or ""
		$scope.perpage   = PERPAGE
		searchResource.users(_.extend($location.search(),{role: "user"})).success (payload) ->
			$scope.results = payload
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.undertreatment =  ->
			$location.search _.extend($location.search(),{treatment: $scope.treatment})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q, role: "user"}
			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				userResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Patient has been deleted successfully!"
					searchResource.users(_.extend($location.search(),{role: "user"})).success (payload) ->
						$scope.results = payload
			return

		$scope.edit = (id) ->
			$location.path "/patient/" + id
			return


		return

]

.controller "UsersCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"securityService"
	"searchResource"
	"userResource"
	"PERPAGE"
	($rootScope, $scope, $location, securityService, searchResource, userResource, PERPAGE) ->
		$scope.perpage     = PERPAGE
		$scope.q           = $location.search().q or ""
		$scope.role        = $location.search().role or ""
		$scope.sort        = $location.search().sort or ""
		$scope.order       = $location.search().order or ""
		$scope.currentUser = securityService.requestCurrentUser()
		searchResource.users($location.search()).success (payload) ->
			$scope.results = payload
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				role: $scope.role
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q, role: $scope.role}
			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				userResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "User has been deleted successfully!"
					searchResource.users($location.search()).success (payload) ->
						$scope.results = payload
			return

		$scope.edit = (id) ->
			$location.path "/users/" + id
			return

		return
]


.controller('DashboardCtrl', [
	"$rootScope"
	"$scope"
	"$modal"
	"treatmentResource"
	"diagnosticResource"
	"roomResource"
	"sessionResource"
	($rootScope, $scope, $modal, treatmentResource, diagnosticResource, roomResource, sessionResource) ->
		roomResource.search({status:1}).success (payload) ->
			$scope.rooms = payload.data
		diagnosticResource.search({status:1}).success (payload) ->
			$scope.diagnostics = payload.data
		treatmentResource.search({active:1}).success (payload) ->
			$scope.treatments = payload.data
		sessionResource.search({}).success (payload) ->
			$scope.sessions = payload.data
		$scope.new = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/phonedate.html"
				controller: scheduleDialogController = [
					"$scope"
					"$modalInstance"
					"searchResource"
					"sessionResource"
					($scope, $modalInstance, searchResource, sessionResource) ->
						$scope.session     = {id:""}
						$scope.rooms       = $parentScope.rooms
						$scope.treatments  = $parentScope.treatments
						$scope.diagnostics = $parentScope.diagnostics
						$scope.mindate     = new Date()
						$scope.dateOptions =
							startingDay: 1
							showWeeks: "false"
						$scope.getPatient = (val) ->
							searchResource.users({'role': "user", q: val}).then (payload) ->
								patients = []
								angular.forEach payload.data.data, (item) ->
									patients.push {id:item.id, fullname: item.first_name + ' ' + item.last_name}
									return
								return patients

						$scope.open = ($event) ->
							$event.preventDefault()
							$event.stopPropagation()
							$scope.opened = true
							return
						$scope.loadEmployees = (treatment) ->
							$scope.loadingEmployees = true
							$scope.employees = []
							searchResource.users({'role': treatment.role}).success (payload) ->
								$scope.employees = payload.data
								$scope.loadingEmployees = false
								return

						$scope.$watch "session.patient", (patient)->
							if patient and patient.id
								$scope.loadDiagnostics(patient.id)

						$scope.loadDiagnostics = (patient_id) ->
							diagnosticResource.search({uid: patient_id}).success (payload) ->
								$scope.diagnostics = payload.data || []

						# save
						$scope.submit = ->
							if $scope.session.id
								sessionResource.update($scope.session.id, $scope.session).success (payload) ->
									$rootScope.$broadcast "success", "Schedule has been updated successfully!"
									$modalInstance.close payload
									return
								return
							else
								sessionResource.create(_.extend($scope.session,{})).success (payload) ->
									$rootScope.$broadcast "success", "Schedule has been created successfully!"
									$modalInstance.close payload
									return
								return

						$scope.dismiss = ->
							$modalInstance.close()
							return
						return

				]
			)
])


.controller "RoomsCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"roomResource"
	"PERPAGE"
	($rootScope, $scope, $location, $modal, roomResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.order   = $location.search().order or ""
		$scope.limit   = $location.search().limit or ""
		$scope.status  = $location.search().status or ""
		roomResource.search($location.search()).success (payload) ->
			$scope.results = payload
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}

			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				roomResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Room has been deleted successfully!"
					roomResource.search($location.search()).success (payload) ->
						$scope.results = payload
						return
			return

		$scope.open = (id) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/room.html"
				controller: roomDialogController = [
					"$scope"
					"$modalInstance"
					"roomResource"
					($scope, $modalInstance, roomResource) ->
						if !parseInt(id)
							$scope.room = {name:""}
						else
							roomResource.get(id).success (payload) ->
								$scope.room = payload
								return

						$scope.submit = ->
							if $scope.room.id
								roomResource.update($scope.room.id, $scope.room).success (payload) ->
									$rootScope.$broadcast "success", "Room has been updated successfully!"
									$modalInstance.close payload
									return
							else
								roomResource.create($scope.room).success (payload) ->
									$rootScope.$broadcast "success", "Room has been created successfully!"
									$modalInstance.close payload
									return
							return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (room) ->
				if room
					roomResource.search($location.search()).success (payload) ->
						$scope.results = payload
						return
				return
			return

		$scope.setStatus = (status, rooms) ->
			angular.forEach rooms, (room) ->
				roomResource.update(room.id,
					status: status
				).success (payload) ->
					$location.search _.extend($location.search(),
						refresh: (new Date()).getTime()
					)
					return

				return

			return

		return
]

.controller "NewsCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"newsResource"
	"PERPAGE"
	($rootScope, $scope, $location, $modal, newsResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.order   = $location.search().order or ""
		$scope.limit   = $location.search().limit or ""
		$scope.title   = $location.search().title or ""
		newsResource.search($location.search()).success (payload) ->
			$scope.results = payload
			$scope.lastNews = payload.data[0]
			return
		# get last news


		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				newsResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Article has been deleted successfully!"
					newsResource.search($location.search()).success (payload) ->
						$scope.results = payload
						return
			return

		$scope.open = (id) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				size: 'lg'
				templateUrl: "html/modal/news.html"
				controller: newsDialogController = [
					"$scope"
					"$modalInstance"
					"newsResource"
					($scope, $modalInstance, newsResource) ->
						if !parseInt(id)
							$scope.news = {title:""}
						else
							newsResource.get(id).success (payload) ->
								$scope.news = payload
								return

						$scope.submit = ->
							if $scope.news.id
								newsResource.update($scope.news.id, $scope.news).success (payload) ->
									$rootScope.$broadcast "success", "Article has been updated successfully!"
									$modalInstance.close payload
									return
							else
								newsResource.create($scope.news).success (payload) ->
									$rootScope.$broadcast "success", "Article has been created successfully!"
									$modalInstance.close payload
									return
							return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (news) ->
				newsResource.search($location.search()).success (payload) ->
					$scope.results = payload
					return
				return
			return

		# Read news on list
		$scope.read = (news) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				size: 'lg'
				templateUrl: "html/modal/news-read.html"
				controller: newsReadDialogController = [
					"$scope"
					"$modalInstance"
					($scope, $modalInstance) ->
						$scope.news = news
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)

]

.controller "ReferersCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"refererResource"
	"PERPAGE"
	($rootScope, $scope, $location, $modal, refererResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.limit   = $location.search().limit or ""
		$scope.order   = $location.search().order or ""
		$scope.status  = $location.search().status or ""
		refererResource.search($location.search()).success (payload) ->
			$scope.results = payload
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				refererResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Referer has been deleted successfully!"
					refererResource.search($location.search()).success (payload) ->
						$scope.results = payload
						return
			return

		$scope.open = (id) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/referer.html"
				controller: refererDialogController = [
					"$scope"
					"$modalInstance"
					"refererResource"
					($scope, $modalInstance, refererResource) ->
						if !parseInt(id)
							$scope.referer = {name:""}
						else
							refererResource.get(id).success (payload) ->
								$scope.referer = payload
								return

						$scope.submit = ->
							if $scope.referer.id
								refererResource.update($scope.referer.id, $scope.referer).success (payload) ->
									$rootScope.$broadcast "success", "Referer has been updated successfully!"
									$modalInstance.close()
									return
							else
								refererResource.create($scope.referer).success (payload) ->
									$rootScope.$broadcast "success", "Referer has been created successfully!"
									$modalInstance.close()
									return
							return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then ->
				refererResource.search($location.search()).success (payload) ->
					$scope.results = payload
					return
				return
			return


		return
]

.controller "TreatmentsCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"treatmentResource"
	"PERPAGE"
	($rootScope, $scope, $location, $modal, treatmentResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.status  = $location.search().status or ""
		$scope.sort    = $location.search().sort or ""
		$scope.order   = $location.search().order or ""
		$scope.limit   = $location.search().limit or ""
		treatmentResource.search($location.search()).success (payload) ->
			$scope.results = payload
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				treatmentResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Treatment has been deleted successfully!"
					treatmentResource.search($location.search()).success (payload) ->
						$scope.results = payload
						return
			return

		$scope.open = (id) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/treatment.html"
				controller: treatmentDialogController = [
					"$scope"
					"$modalInstance"
					"treatmentResource"
					($scope, $modalInstance, treatmentResource) ->
						$scope.roles = [
							"doctor"
							"therapist"
						]
						if !parseInt(id)
							$scope.treatment = {name:""}
						else
							treatmentResource.get(id).success (payload) ->
								$scope.treatment = payload
								return

						$scope.submit = ->
							if $scope.treatment.id
								treatmentResource.update($scope.treatment.id, $scope.treatment).success (payload) ->
									$rootScope.$broadcast "success", "Treatment has been updated successfully!"
									$modalInstance.close()
									return
							else
								treatmentResource.create($scope.treatment).success (payload) ->
									$rootScope.$broadcast "success", "Treatment has been created successfully!"
									$modalInstance.close()
									return
							return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then ->
				treatmentResource.search($location.search()).success (payload) ->
					$scope.results = payload
					return
				return
			return

		$scope.setStatus = (status, treatments) ->
			angular.forEach treatments, (treatment) ->
				treatmentResource.update(treatment.id,
					status: status
				).success (payload) ->
					$location.search _.extend($location.search(),
						refresh: (new Date()).getTime()
					)
					return

				return

			return

		return
]

.controller "PatientBondCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"$routeParams"
	"bondResource"
	"userResource"
	($rootScope, $scope, $location, $modal, $routeParams, bondResource, userResource) ->
		id = $routeParams.id
		if !parseInt(id)
			id = "me"
			userResource.get(id, {}).success (payload) ->
				$scope.user = payload
				return

		$scope.q      = $location.search().q or ""
		$scope.status = $location.search().status or ""
		$scope.sort   = $location.search().sort or ""
		$scope.order  = $location.search().order or ""
		bondResource.search(_.extend($location.search(),{user_id: id})).success (payload) ->
			$scope.results = payload
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{user_id: id, page: page})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.remove = (bid) ->
			x = confirm "Are you sure you want to remove?"
			if x
				bondResource.remove(bid).success (payload) ->
					$rootScope.$broadcast "success", "Bond has been removed successfully!"
					bondResource.search(_.extend($location.search(),{user_id: id})).success (payload) ->
						$scope.results = payload
						return
					return
			return

		$scope.associatebond = ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/associatebond.html"
				controller: bondDialogController = [
					"$scope"
					"$modalInstance"
					"bondResource"
					($scope, $modalInstance, bondResource) ->
						bondResource.search({user_id: id}).success (payload) ->
							$scope.bonds = payload.data
							return

						$scope.submit = ->
							bondResource.associate(_.extend($scope.bond,{user_id:id})).success (payload) ->
								$rootScope.$broadcast "success", "Bond has been associated successfully!"
								$modalInstance.close payload
								return
							return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (bond) ->
				if bond
					bondResource.search(_.extend($location.search(),{user_id: id})).success (payload) ->
						$scope.results = payload
						return
					return

		$scope.new = (id) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/patientbond.html"
				controller: bondDialogController = [
					"$scope"
					"$modalInstance"
					"bondResource"
					($scope, $modalInstance, bondResource) ->
						$scope.bond = {id:""}
						bondResource.bondtype({status:1}).success (payload) ->
							$scope.bonds = payload.data
							return

						$scope.submit = ->
							bondResource.add({user_id:id, bond: $scope.bond}).success (payload) ->
								$rootScope.$broadcast "success", "Bond has been added successfully!"
								$modalInstance.close payload
								return
							return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (bond) ->
				if bond
					bondResource.search(_.extend($location.search(),{user_id: id})).success (payload) ->
						$scope.results = payload
						return
					return

		return
]

.controller "PatientAmendmentCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"$routeParams"
	"invoiceResource"
	"userResource"
	($rootScope, $scope, $location, $modal, $routeParams, invoiceResource, userResource) ->
		id = $routeParams.id
		if !parseInt(id)
			id = "me"
			userResource.get(id, {}).success (payload) ->
				$scope.user = payload
				return

		$scope.q      = $location.search().q or ""
		$scope.status = $location.search().status or ""
		$scope.sort   = $location.search().sort or ""
		$scope.order  = $location.search().order or ""
		invoiceResource.getInvoice(_.extend($location.search(),{user_id: id})).success (payload) ->
			$scope.results = payload
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{user_id: id, page: page})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}

		return
]

.controller "PatientInvoicesCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"$routeParams"
	"bondResource"
	"invoiceResource"
	"userResource"
	"PERPAGE"
	($rootScope, $scope, $location, $modal, $routeParams, bondResource, invoiceResource, userResource, PERPAGE) ->
		$scope.perpage     = PERPAGE
		$scope.q           = $location.search().q or ""
		$scope.status      = $location.search().status or ""
		$scope.sort        = $location.search().sort or ""
		$scope.order       = $location.search().order or ""
		$scope.limit       = $location.search().limit or ""
		id = $routeParams.id
		if !parseInt(id)
			id = "me"
		userResource.get(id, {}).success (payload) ->
			$scope.user = payload
			return
		bondResource.search(_.extend($location.search(),{user_id: id})).success (payload) ->
			$scope.bonds = payload
		invoiceResource.getInvoice(_.extend($location.search(),{user_id: id})).success (payload) ->
			$scope.invoices = payload
		invoiceResource.getAmendments(_.extend($location.search(),{user_id: id})).success (payload) ->
			$scope.amendments = payload

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{user_id: id, page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.choiceInvoice = (invoice) ->
			$scope.amendmentinvoice = invoice
		$scope.doAmendment = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/amendment.html"
				controller: amendmentDialogController = [
					"$scope"
					"$modalInstance"
					"invoiceResource"
					($scope, $modalInstance, invoiceResource) ->
						$scope.invoice = $parentScope.amendmentinvoice
						$scope.submit = ->
							invoiceResource.sendAmendments(_.extend($scope.amendment,{invoice:$scope.invoice})).success (payload) ->
								$rootScope.$broadcast "success", "Amendment has been sent successfully!"
								$modalInstance.close payload
								return
							return
						$scope.dismiss = ->
							$modalInstance.close()

						return
				]
			)
			modal.result.then (amendment) ->
				if amendment
					invoiceResource.getAmendments(_.extend($location.search(),{user_id: id})).success (payload) ->
						$scope.amendments = payload
						return
					return

		$scope.choiceBond = (bond) ->
			$scope.sendinvoice = bond

		$scope.sendInvoice = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/sentinvoice.html"
				controller: sentinvoiceDialogController = [
					"$scope"
					"$modalInstance"
					"invoiceResource"
					($scope, $modalInstance, invoiceResource) ->
						$scope.bond = $parentScope.sendinvoice
						$scope.invoice =
							name: $parentScope.user.meta.first_name + " " + $parentScope.user.meta.last_name
							address: $parentScope.user.meta.address1
							fiscalcode: $parentScope.user.meta.national_id
						$scope.submit = ->
							invoiceResource.sendInvoice(_.extend($scope.invoice,{bond:$scope.bond})).success (payload) ->
								$rootScope.$broadcast "success", "Sent has been sent successfully!"
								$modalInstance.close payload
								return
							return
						$scope.dismiss = ->
							$modalInstance.close()

						return
				]
			)
			modal.result.then (invoice) ->
				if invoice
					invoiceResource.getInvoice(_.extend($location.search(),{user_id: id})).success (payload) ->
						$scope.invoices = payload
						return
					return

		$scope.viewInvoice = (invoice) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/invoice.html"
				controller: viewInvoiceDialogController = [
					"$scope"
					"$modalInstance"
					($scope, $modalInstance) ->
						$scope.invoice = invoice
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
		return
]


.controller "ClientInvoicesCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"$routeParams"
	"bondResource"
	"invoiceResource"
	"userResource"
	"PERPAGE"
	($rootScope, $scope, $location, $modal, $routeParams, bondResource, invoiceResource, userResource, PERPAGE) ->
		$scope.perpage     = PERPAGE
		$scope.q           = $location.search().q or ""
		$scope.status      = $location.search().status or ""
		$scope.sort        = $location.search().sort or ""
		$scope.order       = $location.search().order or ""
		$scope.limit       = $location.search().limit or ""

		bondResource.search($location.search()).success (payload) ->
			$scope.bonds = payload
		invoiceResource.getInvoice($location.search()).success (payload) ->
			$scope.invoices = payload
		invoiceResource.getAmendments($location.search()).success (payload) ->
			$scope.amendments = payload

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.choiceInvoice = (invoice) ->
			$scope.amendmentinvoice = invoice
		$scope.doAmendment = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/amendment.html"
				controller: amendmentDialogController = [
					"$scope"
					"$modalInstance"
					"invoiceResource"
					($scope, $modalInstance, invoiceResource) ->
						$scope.invoice = $parentScope.amendmentinvoice
						$scope.amendment = {amount: $scope.invoice.amount}

						$scope.submit = ->
							invoiceResource.sendAmendments(_.extend($scope.amendment,{invoice:$scope.invoice})).success (payload) ->
								$rootScope.$broadcast "success", "Amendment has been sent successfully!"
								$modalInstance.close payload
								return
							return
						$scope.dismiss = ->
							$modalInstance.close()

						return
				]
			)
			modal.result.then (amendment) ->
				if amendment
					invoiceResource.getAmendments($location.search()).success (payload) ->
						$scope.amendments = payload
						return
					return

		$scope.removelast = ->
			lastItem = $scope.invoices.data[$scope.invoices.data.length - 1]
			# console.log lastItem
			x = confirm "Are you sure you want to delete?"
			if x
				invoiceResource.deleteInvoice(lastItem.id).success (payload) ->
					$rootScope.$broadcast "success", "Invoice has been deleted successfully!"
					invoiceResource.getInvoice($location.search()).success (payload) ->
						$scope.invoices = payload
						return
			return

		$scope.updateAmount = ->
			lastItem = $scope.amendments.data[$scope.amendments.data.length - 1]
			# console.log lastItem
			x = confirm "Are you sure you want to delete?"
			if x
				invoiceResource.removeAmendments(lastItem.id, $scope.amendments).success (payload) ->
					$rootScope.$broadcast "success", "Invoice has been deleted successfully!"
					invoiceResource.getAmendments($location.search()).success (payload) ->
						$scope.amendments = payload
						return
			return


		$scope.choiceBond = (bond) ->
			$scope.sendinvoice = bond

		$scope.sendInvoice = ->
			$parentScope = $scope
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/sentinvoice.html"
				controller: sentinvoiceDialogController = [
					"$scope"
					"$modalInstance"
					"invoiceResource"
					($scope, $modalInstance, invoiceResource) ->
						$scope.bond = $parentScope.sendinvoice
						$scope.invoice =
							name: $parentScope.sendinvoice.patient
							address:  $parentScope.sendinvoice.address
							fiscalcode: $parentScope.sendinvoice.fiscalcode
						$scope.submit = ->
							invoiceResource.sendInvoice(_.extend($scope.invoice,{bond:$scope.bond})).success (payload) ->
								$rootScope.$broadcast "success", "Sent has been sent successfully!"
								$modalInstance.close payload
								return
							return
						$scope.dismiss = ->
							$modalInstance.close()

						return
				]
			)
			modal.result.then (invoice) ->
				if invoice
					invoiceResource.getInvoice($location.search()).success (payload) ->
						$scope.invoices = payload
						return
					return

		$scope.viewInvoice = (invoice) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/invoice.html"
				controller: viewInvoiceDialogController = [
					"$scope"
					"$modalInstance"
					($scope, $modalInstance) ->
						$scope.invoice = invoice
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
		return
]

.controller "BondsCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"bondResource"
	"PERPAGE"
	($rootScope, $scope, $location, $modal, bondResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.order   = $location.search().order or ""
		$scope.limit   = $location.search().limit or ""
		$scope.status  = $location.search().status or ""
		bondResource.bondtype($location.search()).success (payload) ->
			$scope.results = payload
		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				bondResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Bond has been deleted successfully!"
					bondResource.bondtype($location.search()).success (payload) ->
						$scope.results = payload
						return
			return

		$scope.open = (id) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/bond.html"
				controller: bondsDialogController = [
					"$scope"
					"$location"
					"$modalInstance"
					"bondResource"
					($scope, $location, $modalInstance, bondResource) ->
						if !parseInt(id)
							$scope.bond = {name:""}
						else
							bondResource.get(id).success (payload) ->
								$scope.bond = payload
								return

						$scope.submit = ->
							if $scope.bond.id
								bondResource.update($scope.bond.id, $scope.bond).success (payload) ->
									$rootScope.$broadcast "success", "Bond has been updated successfully!"
									$modalInstance.close payload
									return
							else
								bondResource.create($scope.bond).success (payload) ->
									$rootScope.$broadcast "success", "Bond has been created successfully!"
									$modalInstance.close payload
									return
							return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (bond) ->
				if bond
					bondResource.bondtype($location.search()).success (payload) ->
						$scope.results = payload
						return
				return
			return

		return
]


.controller "PathologiesCtrl", [
	"$rootScope"
	"$scope"
	"$location"
	"$modal"
	"pathologyResource"
	"PERPAGE"
	($rootScope, $scope, $location, $modal, pathologyResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.order   = $location.search().order or ""
		$scope.limit   = $location.search().limit or ""
		$scope.status  = $location.search().status or ""
		pathologyResource.search($location.search()).success (payload) ->
			$scope.results = payload

		$scope.open = (id) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/pathology.html"
				controller: pathologiesDialogController = [
					"$scope"
					"$modalInstance"
					"pathologyResource"
					($scope, $modalInstance, pathologyResource) ->
						if !parseInt(id)
							$scope.pathologie = {name:""}
						else
							pathologyResource.get(id).success (payload) ->
								$scope.pathologie = payload
								return

						$scope.submit = ->
							if $scope.pathologie.id
								pathologyResource.update($scope.pathologie.id, $scope.pathologie).success (payload) ->
									$rootScope.$broadcast "success", "Pathologie has been updated successfully!"
									$modalInstance.close payload
									return
							else
								pathologyResource.create($scope.pathologie).success (payload) ->
									$rootScope.$broadcast "success", "Pathologie has been created successfully!"
									$modalInstance.close payload
									return

							return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)
			modal.result.then (pathologie) ->
				if pathologie
					pathologyResource.search($location.search()).success (payload) ->
						$scope.results = payload
						return
				return
			return

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})
			return

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort  = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

			return
		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
			return

		$scope.getTotalPages = ->
			$scope.results.last_page

		$scope.search = ->
			$location.search {q: $scope.q}
			return

		$scope.delete = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				pathologyResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Pathologie has been deleted successfully!"
					pathologyResource.search($location.search()).success (payload) ->
						$scope.results = payload
						return
			return

		return
]

.controller "MessagesCtrl", [
	"$scope"
	"$rootScope"
	"$modal"
	"$location"
	"messageResource"
	"userResource"
	"PERPAGE"
	($scope, $rootScope, $modal, $location, messageResource, userResource, PERPAGE) ->
		$scope.perpage = PERPAGE
		$scope.q       = $location.search().q or ""
		$scope.sort    = $location.search().sort or ""
		$scope.order   = $location.search().order or ""
		$scope.limit   = $location.search().limit or ""
		userResource.getMe().success (payload) ->
			$scope.user = payload
			messageResource.search({receiver: $scope.user.id}).success (payload) ->
				$scope.messages = payload
			messageResource.search({sender: $scope.user.id}).success (payload) ->
				$scope.sents = payload

		$scope.open = (id) ->
			modal = $modal.open(
				backdrop: true
				keyboard: true
				templateUrl: "html/modal/message.html"
				controller: messageDialogController = [
					"$scope"
					"$modalInstance"
					"messageResource"
					($scope, $modalInstance, messageResource) ->
						if !parseInt(id)
							$scope.message = {}
						else
							messageResource.get(id).success (payload) ->
								$scope.message = payload
								return
						$scope.dismiss = ->
							$modalInstance.close()
							return
						return
				]
			)

		$scope.deleteInbox = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				messageResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Message has been deleted successfully!"
					messageResource.search({receiver: $scope.user.id}).success (payload) ->
						$scope.messages = payload
						return
			return

		$scope.deleteSent = (id) ->
			x = confirm "Are you sure you want to delete?"
			if x
				messageResource.delete(id).success (payload) ->
					$rootScope.$broadcast "success", "Message has been deleted successfully!"
					messageResource.search({sender: $scope.user.id}).success (payload) ->
						$scope.sents = payload
						return
			return

		$scope.search = ->
			$location.search {q: $scope.q}

		$scope.goTo = (page) ->
			$location.search _.extend($location.search(),{page: page})

		$scope.sortBy = (sort) ->
			if $scope.sort isnt sort
				$scope.sort = sort
				$scope.order = "asc"
			else
				$scope.order = (if $scope.order is "asc" then "desc" else "asc")
			$location.search
				status: $scope.status
				sort: $scope.sort
				order: $scope.order
				q: $scope.q

		$scope.changeLimit = (limit) ->
			$location.search _.extend($location.search(),{limit: limit})
]

.controller "MessageCtrl", [
	"$scope"
	"$location"
	"$rootScope"
	"$routeParams"
	"messageResource"
	($scope, $location, $rootScope, $routeParams, messageResource) ->
		$scope.message = {}
		id = $routeParams.id
		if !parseInt(id)
			$location.path "messages"

		messageResource.get(id).success (payload) ->
			$scope.message_id = payload
			return
		$scope.reply = ->
			messageResource.create(_.extend($scope.message,{receiver:$scope.message_id.sender, topic:"Re: " + $scope.message_id.topic})).success (payload) ->
				$rootScope.$broadcast "success", "Message has been send successfully!"
				$location.path "messages"
				return
			return
]

.controller "ComposeMessageCtrl", [
	"$rootScope"
	"$scope"
	"messageResource"
	"searchResource"
	($rootScope, $scope, messageResource, searchResource) ->
		$scope.send = ->
			$scope.form.$setDirty()
			if $scope.form.$valid
				messageResource.create(_.extend($scope.message,{receiver:$scope.receiver.id})).success (payload) ->
					$rootScope.$broadcast "success", "Message has been send successfully!"
					$scope.message = {}
		# get list user
		$scope.discard = ->
			$scope.message = {}
		$scope.getUsers = (val) ->
			$scope.loadingType = true
			searchResource.users({q: val}).then (payload) ->
				users = []
				angular.forEach payload.data.data, (item) ->
					users.push {id:item.id, fullname: item.first_name + ' ' + item.last_name}
					return
				$scope.loadingType = false
				return users

]

.controller("FlashCtrl",[
	"$scope"
	"$location"
	"$rootScope"
	"DELAY"
	"uniqueIdService"
	"$sce"
	($scope, $location, $rootScope, DELAY, uniqueIdService, $sce) ->
		$scope.messages = {}
		$scope.$on "success", (event, msg) ->
			id = uniqueIdService.generate()
			$scope.messages[id] =
				class: "alert-success"
				msg: $sce.trustAsHtml(msg)

			setTimeout (->
				$scope.close id
				return
			), DELAY
			return

		$scope.$on "notify", (event, msg) ->
			id = uniqueIdService.generate()
			$scope.messages[id] =
				class: "alert-info"
				msg: $sce.trustAsHtml(msg)

			setTimeout (->
				$scope.close id
				return
			), DELAY
			return

		$scope.$on "warning", (event, msg) ->
			id = uniqueIdService.generate()
			$scope.messages[id] =
				class: "alert-warning"
				msg: $sce.trustAsHtml(msg)

			setTimeout (->
				$scope.close id
				return
			), DELAY
			return

		$scope.$on "error", (event, msg) ->
			id = uniqueIdService.generate()
			$scope.messages[id] =
				class: "alert-danger"
				msg: $sce.trustAsHtml(msg)

			setTimeout (->
				$scope.close id
				return
			), DELAY
			return

		$scope.close = (id) ->
			delete $scope.messages[id]  if $scope.messages.hasOwnProperty(id)
			return

		return
])


.controller 'AccountingStatisticsCtrl', [
	"$scope"
	"$location"
	"movementsResource"
	($scope, $location, movementsResource) ->
		$scope.today = new Date()
		$scope.end    = $location.search().end or ""
		$scope.begin   = $location.search().begin or ""
		$scope.dateOptions =
			startingDay: 1
			showWeeks: "false"

		$scope.open = ($event) ->
			$event.preventDefault()
			$event.stopPropagation()
			$scope.opened = true
			return

		$scope.dateFilter = ->
			if $scope.end and $scope.begin
				if $scope.begin > $scope.end
					$location.search({begin:$scope.end, end: $scope.begin})
				else
					$location.search({begin:$scope.begin, end: $scope.end})
			return
		$scope.dateLength = ->
			end = new Date($scope.end)
			begin = new Date($scope.begin)
			end.getDate() - begin.getDate()
		
		$scope.data1 = $scope.data2 = $scope.data3 = []
		##
		#
		##
		lineChart1 = {}
		lineChart1.data1 = [[1,15],[2,20],[3,14],[4,10],[5,10],[6,20],[7,28],[8,26],[9,22],[10,23],[11,24]]
		lineChart1.data2 = [[1,9],[2,15],[3,17],[4,21],[5,16],[6,15],[7,13],[8,15],[9,29],[10,21],[11,29]]
		$scope.lineChart = [
			data: lineChart1.data1
			label: 'Invoice Client'
		,
			data: lineChart1.data2
			label: 'Invoice Provider'
			lines:
				fill: false
		]

		# Donut Chart
		card = money = bond = receive = sent = 0
		$scope.donutChart = $scope.lineChart = []
		movementsResource.search($location.search()).success (payload) ->
			length = $scope.dateLength()
			for i in length by -1
				$scope.data1.push [i,i]
			$scope.results = results = payload
			angular.forEach results.data, (value) ->
				card += parseFloat(value.amount)  if value.payment_type == 'card' and value.invoices_providers_id != 0
				money += parseFloat(value.amount)  if value.payment_type == 'money' and value.invoices_providers_id != 0
				sent += parseFloat(value.amount)  if value.invoices_providers_id == 0
				receive += parseFloat(value.amount)  if value.invoices_providers_id != 0
				
				return
			$scope.lineChart = [
				data: sent
				label: 'Invoice Client'
			,
				data: receive
				label: 'Invoice Provider'
			]
			$scope.donutChart = [
				label: " Positivie Card"
				data: Math.abs(card)
			,
				label: " Positivie Cash"
				data: Math.abs(money)
			]
		return
]
