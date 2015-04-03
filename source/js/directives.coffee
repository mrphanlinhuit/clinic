angular.module("App.directives", [])

.directive("appVersion", [
	"version"
	(version) ->
		return (scope, elm, attrs) ->
			elm.text version
])

.directive "fixnumeric", ->
	restrict: "A"
	require: "ngModel"
	scope:
		model: "=ngModel"
	link: (scope, element, attrs, ngModelCtrl) ->
		scope.model = parseInt(scope.model)  if scope.model
		return

.directive("maskinput", ->
	restrict: "A"
	link: (scope, element, attr) ->
		element.inputmask()
		return
)

.directive "logOut", [
	"$location"
	"securityService"
	"DEFAULT_ROUTE"
	($location, securityService, DEFAULT_ROUTE) ->
		return (scope, element, attrs) ->
			element.bind "click", ->
				securityService.destroySession()
				scope.$apply ->
						$location.path DEFAULT_ROUTE
						return
					return
			return
]

.directive "goClick", [
	"$location"
	($location) ->
		return (scope, element, attrs) ->
			path = undefined
			attrs.$observe "goClick", (val) ->
				path = val
				return

			element.bind "click", ->
				scope.$apply ->
					$location.path path
					return
				return
]

.directive 'taskFocus', [
	'$timeout'
	($timeout) ->
		return {
			link: (scope, ele, attrs) ->
				scope.$watch(attrs.taskFocus, (newVal) ->
					if newVal
						$timeout( ->
							ele[0].focus()
						, 0, false)
				)
		}
]

# Chart
.directive 'donutchart', [ ->
	return {
		restrict: 'E'
		link: (scope, ele, attrs) ->
			chart = null
			options =
				series:
					pie:
						show: true
						innerRadius: 0.5
				legend:
					show: true
				grid:
					hoverable: true
					clickable: true
				colors: ["#60CD9B", "#66B5D7", "#EEC95A","#E87352"]
				tooltip: true
				tooltipOpts:
					content: "%p.0%, %s"
					defaultTheme: false
			data = scope[attrs.ngModel]
			scope.$watch "donutChart", (v) ->
				unless chart
					chart = $.plot(ele[0], v, options);
					ele.show()
				else
					chart.setData v
					chart.setupGrid()
					chart.draw()
	}
]

.directive 'linechart', [ ->
	return {
		restrict: 'E'
		link: (scope, ele, attrs) ->
			chart = null
			options = {
				series:
					pie:
						show: true
				legend:
					show: true
				grid:
					hoverable: true
					clickable: true
				colors: ["#60CD9B", "#66B5D7", "#EEC95A","#E87352"]
				tooltip: true
				tooltipOpts:
					content: "%p.0%, %s"
					defaultTheme: false
			}

			data = scope[attrs.ngModel]
			scope.$watch "lineChart", (v) ->
				unless chart
					chart = $.plot(ele[0], v, options);
					ele.show()
				else
					chart.setData v
					chart.setupGrid()
					chart.draw()
	}
]

.directive "imgOnLoad", ->
	restrict: "C"
	link: (scope, element, attrs) ->
		element.bind "load", (e) ->
			element.addClass "loaded"
			return


.directive 'imgHolder', [ ->
	return {
		restrict: 'A'
		link: (scope, ele, attrs) ->
			Holder.run(
				images: ele[0]
			)
	}
]

# add background and some style just for specific page
.directive('customBackground', () ->
	return {
		restrict: "A"
		controller: [
			'$scope', '$element', '$location'
			($scope, $element, $location) ->
				path = ->
					return $location.path()

				addBg = (path) ->
					# remove all the classes
					$element.removeClass('body-home body-special body-tasks body-lock')

					# add certain class based on path
					switch path
						when '/' then $element.addClass('body-home')
						when '/404', '/500', '/user/lock', '/user/signin', '/user/signup', '/user/forgot' then $element.addClass('body-special')
						when '/user/lock-screen' then $element.addClass('body-special body-lock')
						when '/tasks' then $element.addClass('body-tasks')

				addBg( $location.path() )

				$scope.$watch(path, (newVal, oldVal) ->
					if newVal is oldVal
						return
					addBg($location.path())
				)
		]
	}
)

# for mini style NAV
.directive('toggleMinNav', [
	'$rootScope'
	($rootScope) ->
		return {
			restrict: 'A'
			link: (scope, ele, attrs) ->
				app = $('#app')
				$window = $(window)
				# nav = $('#nav ul') # failt to get it
				$nav = $('#nav-container')
				$content = $('#content')
				# console.log($nav)

				ele.on('click', (e) ->
					if app.hasClass('nav-min')
						app.removeClass('nav-min')
					else
						app.addClass('nav-min')
						$rootScope.$broadcast('minNav:enabled')

					e.preventDefault()
				)

				# removeClass('nav-min') when size < $screen-sm
				# Timer = undefined
				updateClass = ->
					width = $window.width()
					# console.log(width)
					if width < 768 then app.removeClass('nav-min')
				$window.resize( () ->
					clearTimeout(t)
					t = setTimeout(updateClass, 300)
				)
		}
])
# for accordion/collapse style NAV
.directive('collapseNav', [ ->
	return {
		restrict: 'A'
		link: (scope, ele, attrs) ->
			$lists = ele.find('ul').parent('li') # only target li that has sub ul
			$lists.append('<i class="fa fa-caret-right icon-has-ul"></i>')
			$a = $lists.children('a')
			$listsRest = ele.children('li').not($lists)
			$aRest = $listsRest.children('a')

			app = $('#app')

			$a.on('click', (event) ->

				# disable click event when Nav is in mini style
				if ( app.hasClass('nav-min') ) then return false

				$this = $(this)
				$parent = $this.parent('li')
				$lists.not( $parent ).removeClass('open').find('ul').slideUp()
				$parent.toggleClass('open').find('ul').slideToggle()

				event.preventDefault()
			)

			$aRest.on('click', (event) ->
				$lists.removeClass('open').find('ul').slideUp()
			)

			# reset collapse NAV, sub Ul should slideUp
			scope.$on('minNav:enabled', (event) ->
				$lists.removeClass('open').find('ul').slideUp()
			)

	}
])
# Add 'active' class to li based on url, muli-level supported, jquery free
.directive('highlightActive', [ ->
	return {
		restrict: "A"
		controller: [
			'$scope', '$element', '$attrs', '$location'
			($scope, $element, $attrs, $location) ->
				links = $element.find('a')
				path = () ->
					return $location.path()

				highlightActive = (links, path) ->
					path = '#' + path

					angular.forEach(links, (link) ->
						$link = angular.element(link)
						$li = $link.parent('li')
						href = $link.attr('href')

						if ($li.hasClass('active'))
							$li.removeClass('active')
						if path.indexOf(href) is 0
							$li.addClass('active')
					)

				highlightActive(links, $location.path())

				$scope.$watch(path, (newVal, oldVal) ->
					if newVal is oldVal
						return
					highlightActive(links, $location.path())
				)
		]

	}
])

# toggle on-canvas for small screen, with CSS
.directive('toggleOffCanvas', [ ->
	return {
		restrict: 'A'
		link: (scope, ele, attrs) ->
			ele.on('click', ->
				$('#app').toggleClass('on-canvas')
			)
	}
])

.directive('slimScroll', [ ->
	return {
		restrict: 'A'
		link: (scope, ele, attrs) ->
			ele.slimScroll({
				height: attrs.scrollHeight || '100%'
			})
	}
])

# history back button
.directive('goBack', [ ->
	return {
		restrict: "A"
		controller: [
			'$scope', '$element', '$window'
			($scope, $element, $window) ->
				$element.on('click', ->
					$window.history.back()
				)
		]
	}
])


.directive "myCurrentTime", [
	"$interval"
	"dateFilter"
	($interval, dateFilter) ->

		# return the directive link function. (compile function not needed)
		return (scope, element, attrs) ->
			# date format
			# so that we can cancel the time updates

			# used to update the UI
			updateTime = ->
				element.text dateFilter(new Date(), format)
				return
			format = undefined
			stopTime = undefined

			# watch the expression, and update the UI on change.
			scope.$watch attrs.myCurrentTime, (value) ->
				format = value
				updateTime()
				return

			stopTime = $interval(updateTime, 1000)

			# listen on DOM destroy (removal) event, and cancel the next UI update
			# to prevent updating time after the DOM element was removed.
			element.on "$destroy", ->
				$interval.cancel stopTime
				return

			return
]

# Registers a new directive with the compiler
#
.directive 'sessionList', [ ->
	return {
		restrict: 'AE'
		replace: true
		scope:
			prescription: "="
			user: "="
			rooms: "="
		controller: [
			"$rootScope"
			"$scope"
			"$modal"
			"sessionResource"
			($rootScope, $scope, $modal, sessionResource) ->
				# console.log $scope.prescription
				$scope.statuses = []

				$scope.toggleStatus = (id) ->
					idx = $scope.statuses.indexOf(id)
					if idx > -1
						$scope.statuses.splice idx, 1
					else
						$scope.statuses.push id
					return
				$scope.missed = ->
					sessionResource.status({checked: $scope.statuses, status: '-1'}).success (payload)->
						$scope.statuses = []
						$rootScope.$broadcast "success", "Session has been updated missed successfully!"
						sessionResource.search({prescription_id:$scope.prescription.id}).success (payload) ->
							$scope.sessions = payload.data
						return
				$scope.received = ->
					sessionResource.status({checked: $scope.statuses, status: '1'}).success (payload)->
						$scope.statuses = []
						$rootScope.$broadcast "success", "Session has been updated received successfully!"
						sessionResource.search({prescription_id:$scope.prescription.id}).success (payload) ->
							$scope.sessions = payload.data
					return
				$scope.schedule = ->
					$parentScope = $scope
					modal = $modal.open(
						backdrop: true
						keyboard: true
						templateUrl: "html/modal/session.html"
						controller: prescriptionSessionDialogController = [
							"$scope"
							"$modalInstance"
							"sessionResource"
							($scope, $modalInstance, sessionResource) ->
								$scope.rooms   = $parentScope.rooms
								$scope.session = {id:"", patient: $parentScope.user}
								$scope.mindate = new Date()
								$scope.dateOptions =
									startingDay: 1
									showWeeks: "false"
								$scope.open = ($event) ->
									$event.preventDefault()
									$event.stopPropagation()
									$scope.opened = true
									return
								# save
								$scope.submit = ->
									if $scope.session.id
										sessionResource.update($scope.session.id, $scope.session).success (payload) ->
											$rootScope.$broadcast "success", "Session has been updated successfully!"
											$modalInstance.close payload
											return
										return
									else
										sessionResource.create(_.extend($scope.session,{"prescription": $parentScope.prescription, "diagnostic": {id: $parentScope.prescription.diagnostic_id}})).success (payload) ->
											$rootScope.$broadcast "success", "Session has been created successfully!"
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
							sessionResource.search({prescription_id:$scope.prescription.id}).success (payload) ->
								$scope.sessions = payload.data
						return
				$scope.open = (id) ->
					modal = $modal.open(
						backdrop: true
						keyboard: true
						templateUrl: "html/modal/session-note.html"
						controller: noteDialogController = [
							"$rootScope"
							"$scope"
							"$modalInstance"
							"sessionResource"
							($rootScope, $scope, $modalInstance, sessionResource) ->
								if !parseInt(id)
									$scope.session = {note:""}
								else
									sessionResource.get(id).success (payload) ->
										$scope.session = payload
										return

								$scope.submit = ->
									if $scope.session.id
										sessionResource.update($scope.session.id, $scope.session).success (payload) ->
											$rootScope.$broadcast "success", "Note has been updated successfully!"
											$modalInstance.close()
											return
									else
										$rootScope.$broadcast "error", "Note can update!"
										$modalInstance.close()
										return
									return
								$scope.dismiss = ->
									$modalInstance.close()
									return
								return
						]
					)
				sessionResource.search({prescription_id:$scope.prescription.id}).success (payload) ->
					$scope.sessions = payload.data
					return
		]
		templateUrl: 'html/directive/session.html'
	}
]

.directive 'uiSpinner', ->
	link: (scope, ele) ->
		ele.addClass 'ui-spinner'
		ele.spinner()
