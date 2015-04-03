angular.module("App.services", [])

.factory "Session", ->
	get: (key) ->
		record = JSON.parse(sessionStorage.getItem(key))
		return false  unless record
		new Date().getTime() < record.timestamp and JSON.parse(record.value)

	set: (key, val, time = 864000) ->
		expire = time * 60 * 1000
		record =
			value: JSON.stringify(val)
			timestamp: new Date().getTime() + expire

		sessionStorage.setItem key, JSON.stringify(record)
		val

	unset: (key) ->
		sessionStorage.removeItem key

.factory "GenderData", [ ->
	[
		{key: "female", label: "Female"}
		{key: "male", label: "Male"}
	]
]

.factory "StatusData", [ ->
	[
		{key: "pending", label: "pending"}
		{key: "active", label: "active"}
		{key: "inactive", label: "inactive"}
		{key: "banned", label: "banned"}
	]
]

.factory "PostalData", [ ->
	[
		{"key":"01", "name":"Alava"},
		{"key":"02", "name":"Albacete"},
		{"key":"03", "name":"Alicante"},
		{"key":"04", "name":"Almería"},
		{"key":"05", "name":"Avila"},
		{"key":"06", "name":"Badajoz"},
		{"key":"07", "name":"Illes Balears"},
		{"key":"07", "name":"Islas Baleares"},
		{"key":"08", "name":"Barcelona"},
		{"key":"09", "name":"Burgos"},
		{"key":"10", "name":"Cáceres"},
		{"key":"11", "name":"Cádiz"},
		{"key":"12", "name":"Castellón"},
		{"key":"13", "name":"Ciudad Real"},
		{"key":"14", "name":"Córdoba"},
		{"key":"15", "name":"La Coruña"},
		{"key":"16", "name":"Cuenca"},
		{"key":"17", "name":"Gerona"},
		{"key":"18", "name":"Granada"},
		{"key":"19", "name":"Guadalajara"},
		{"key":"20", "name":"Guipuzcoa"},
		{"key":"21", "name":"Huelva"},
		{"key":"22", "name":"Huesca"},
		{"key":"23", "name":"Jaen"},
		{"key":"24", "name":"León"},
		{"key":"25", "name":"Lérida"},
		{"key":"26", "name":"La Rioja"},
		{"key":"27", "name":"Lugo"},
		{"key":"28", "name":"Madrid"},
		{"key":"29", "name":"Málaga"},
		{"key":"30", "name":"Murcia"},
		{"key":"31", "name":"Navarra"},
		{"key":"32", "name":"Orense"},
		{"key":"33", "name":"Asturias"},
		{"key":"34", "name":"Palencia"},
		{"key":"35", "name":"Las Palmas"},
		{"key":"36", "name":"Pontevedra"},
		{"key":"37", "name":"Salamanca"},
		{"key":"38", "name":"S.C.Tenerife"},
		{"key":"39", "name":"Cantabria"},
		{"key":"40", "name":"Segovia"},
		{"key":"41", "name":"Sevilla"},
		{"key":"42", "name":"Soria"},
		{"key":"43", "name":"Tarragona"},
		{"key":"44", "name":"Teruel"},
		{"key":"45", "name":"Toledo"},
		{"key":"46", "name":"Valencia"},
		{"key":"47", "name":"Valladolid"},
		{"key":"48", "name":"Vizcaya"},
		{"key":"49", "name":"Zamora"},
		{"key":"50", "name":"Zaragoza"},
		{"key":"51", "name":"Ceuta"},
		{"key":"52", "name":"Melilla"}
	]
]

.factory "CountryData", [ ->
	[
		{"name":"Afghanistan","key":"AF","country-code":"004"},
		{"name":"Åland Islands","key":"AX","country-code":"248"},
		{"name":"Albania","key":"AL","country-code":"008"},
		{"name":"Algeria","key":"DZ","country-code":"012"},
		{"name":"American Samoa","key":"AS","country-code":"016"},
		{"name":"Andorra","key":"AD","country-code":"020"},
		{"name":"Angola","key":"AO","country-code":"024"},
		{"name":"Anguilla","key":"AI","country-code":"660"},
		{"name":"Antarctica","key":"AQ","country-code":"010"},
		{"name":"Antigua and Barbuda","key":"AG","country-code":"028"},
		{"name":"Argentina","key":"AR","country-code":"032"},
		{"name":"Armenia","key":"AM","country-code":"051"},
		{"name":"Aruba","key":"AW","country-code":"533"},
		{"name":"Australia","key":"AU","country-code":"036"},
		{"name":"Austria","key":"AT","country-code":"040"},
		{"name":"Azerbaijan","key":"AZ","country-code":"031"},
		{"name":"Bahamas","key":"BS","country-code":"044"},
		{"name":"Bahrain","key":"BH","country-code":"048"},
		{"name":"Bangladesh","key":"BD","country-code":"050"},
		{"name":"Barbados","key":"BB","country-code":"052"},
		{"name":"Belarus","key":"BY","country-code":"112"},
		{"name":"Belgium","key":"BE","country-code":"056"},
		{"name":"Belize","key":"BZ","country-code":"084"},
		{"name":"Benin","key":"BJ","country-code":"204"},
		{"name":"Bermuda","key":"BM","country-code":"060"},
		{"name":"Bhutan","key":"BT","country-code":"064"},
		{"name":"Bolivia, Plurinational State of","key":"BO","country-code":"068"},
		{"name":"Bonaire, Sint Eustatius and Saba","key":"BQ","country-code":"535"},
		{"name":"Bosnia and Herzegovina","key":"BA","country-code":"070"},
		{"name":"Botswana","key":"BW","country-code":"072"},
		{"name":"Bouvet Island","key":"BV","country-code":"074"},
		{"name":"Brazil","key":"BR","country-code":"076"},
		{"name":"British Indian Ocean Territory","key":"IO","country-code":"086"},
		{"name":"Brunei Darussalam","key":"BN","country-code":"096"},
		{"name":"Bulgaria","key":"BG","country-code":"100"},
		{"name":"Burkina Faso","key":"BF","country-code":"854"},
		{"name":"Burundi","key":"BI","country-code":"108"},
		{"name":"Cambodia","key":"KH","country-code":"116"},
		{"name":"Cameroon","key":"CM","country-code":"120"},
		{"name":"Canada","key":"CA","country-code":"124"},
		{"name":"Cape Verde","key":"CV","country-code":"132"},
		{"name":"Cayman Islands","key":"KY","country-code":"136"},
		{"name":"Central African Republic","key":"CF","country-code":"140"},
		{"name":"Chad","key":"TD","country-code":"148"},
		{"name":"Chile","key":"CL","country-code":"152"},
		{"name":"China","key":"CN","country-code":"156"},
		{"name":"Christmas Island","key":"CX","country-code":"162"},
		{"name":"Cocos (Keeling) Islands","key":"CC","country-code":"166"},
		{"name":"Colombia","key":"CO","country-code":"170"},
		{"name":"Comoros","key":"KM","country-code":"174"},
		{"name":"Congo","key":"CG","country-code":"178"},
		{"name":"Congo, the Democratic Republic of the","key":"CD","country-code":"180"},
		{"name":"Cook Islands","key":"CK","country-code":"184"},
		{"name":"Costa Rica","key":"CR","country-code":"188"},
		{"name":"Côte d'Ivoire","key":"CI","country-code":"384"},
		{"name":"Croatia","key":"HR","country-code":"191"},
		{"name":"Cuba","key":"CU","country-code":"192"},
		{"name":"Curaçao","key":"CW","country-code":"531"},
		{"name":"Cyprus","key":"CY","country-code":"196"},
		{"name":"Czech Republic","key":"CZ","country-code":"203"},
		{"name":"Denmark","key":"DK","country-code":"208"},
		{"name":"Djibouti","key":"DJ","country-code":"262"},
		{"name":"Dominica","key":"DM","country-code":"212"},
		{"name":"Dominican Republic","key":"DO","country-code":"214"},
		{"name":"Ecuador","key":"EC","country-code":"218"},
		{"name":"Egypt","key":"EG","country-code":"818"},
		{"name":"El Salvador","key":"SV","country-code":"222"},
		{"name":"Equatorial Guinea","key":"GQ","country-code":"226"},
		{"name":"Eritrea","key":"ER","country-code":"232"},
		{"name":"Estonia","key":"EE","country-code":"233"},
		{"name":"Ethiopia","key":"ET","country-code":"231"},
		{"name":"Falkland Islands (Malvinas)","key":"FK","country-code":"238"},
		{"name":"Faroe Islands","key":"FO","country-code":"234"},
		{"name":"Fiji","key":"FJ","country-code":"242"},
		{"name":"Finland","key":"FI","country-code":"246"},
		{"name":"France","key":"FR","country-code":"250"},
		{"name":"French Guiana","key":"GF","country-code":"254"},
		{"name":"French Polynesia","key":"PF","country-code":"258"},
		{"name":"French Southern Territories","key":"TF","country-code":"260"},
		{"name":"Gabon","key":"GA","country-code":"266"},
		{"name":"Gambia","key":"GM","country-code":"270"},
		{"name":"Georgia","key":"GE","country-code":"268"},
		{"name":"Germany","key":"DE","country-code":"276"},
		{"name":"Ghana","key":"GH","country-code":"288"},
		{"name":"Gibraltar","key":"GI","country-code":"292"},
		{"name":"Greece","key":"GR","country-code":"300"},
		{"name":"Greenland","key":"GL","country-code":"304"},
		{"name":"Grenada","key":"GD","country-code":"308"},
		{"name":"Guadeloupe","key":"GP","country-code":"312"},
		{"name":"Guam","key":"GU","country-code":"316"},
		{"name":"Guatemala","key":"GT","country-code":"320"},
		{"name":"Guernsey","key":"GG","country-code":"831"},
		{"name":"Guinea","key":"GN","country-code":"324"},
		{"name":"Guinea-Bissau","key":"GW","country-code":"624"},
		{"name":"Guyana","key":"GY","country-code":"328"},
		{"name":"Haiti","key":"HT","country-code":"332"},
		{"name":"Heard Island and McDonald Islands","key":"HM","country-code":"334"},
		{"name":"Holy See (Vatican City State)","key":"VA","country-code":"336"},
		{"name":"Honduras","key":"HN","country-code":"340"},
		{"name":"Hong Kong","key":"HK","country-code":"344"},
		{"name":"Hungary","key":"HU","country-code":"348"},
		{"name":"Iceland","key":"IS","country-code":"352"},
		{"name":"India","key":"IN","country-code":"356"},
		{"name":"Indonesia","key":"ID","country-code":"360"},
		{"name":"Iran, Islamic Republic of","key":"IR","country-code":"364"},
		{"name":"Iraq","key":"IQ","country-code":"368"},
		{"name":"Ireland","key":"IE","country-code":"372"},
		{"name":"Isle of Man","key":"IM","country-code":"833"},
		{"name":"Israel","key":"IL","country-code":"376"},
		{"name":"Italy","key":"IT","country-code":"380"},
		{"name":"Jamaica","key":"JM","country-code":"388"},
		{"name":"Japan","key":"JP","country-code":"392"},
		{"name":"Jersey","key":"JE","country-code":"832"},
		{"name":"Jordan","key":"JO","country-code":"400"},
		{"name":"Kazakhstan","key":"KZ","country-code":"398"},
		{"name":"Kenya","key":"KE","country-code":"404"},
		{"name":"Kiribati","key":"KI","country-code":"296"},
		{"name":"Korea, Democratic People's Republic of","key":"KP","country-code":"408"},
		{"name":"Korea, Republic of","key":"KR","country-code":"410"},
		{"name":"Kuwait","key":"KW","country-code":"414"},
		{"name":"Kyrgyzstan","key":"KG","country-code":"417"},
		{"name":"Lao People's Democratic Republic","key":"LA","country-code":"418"},
		{"name":"Latvia","key":"LV","country-code":"428"},
		{"name":"Lebanon","key":"LB","country-code":"422"},
		{"name":"Lesotho","key":"LS","country-code":"426"},
		{"name":"Liberia","key":"LR","country-code":"430"},
		{"name":"Libya","key":"LY","country-code":"434"},
		{"name":"Liechtenstein","key":"LI","country-code":"438"},
		{"name":"Lithuania","key":"LT","country-code":"440"},
		{"name":"Luxembourg","key":"LU","country-code":"442"},
		{"name":"Macao","key":"MO","country-code":"446"},
		{"name":"Macedonia, the former Yugoslav Republic of","key":"MK","country-code":"807"},
		{"name":"Madagascar","key":"MG","country-code":"450"},
		{"name":"Malawi","key":"MW","country-code":"454"},
		{"name":"Malaysia","key":"MY","country-code":"458"},
		{"name":"Maldives","key":"MV","country-code":"462"},
		{"name":"Mali","key":"ML","country-code":"466"},
		{"name":"Malta","key":"MT","country-code":"470"},
		{"name":"Marshall Islands","key":"MH","country-code":"584"},
		{"name":"Martinique","key":"MQ","country-code":"474"},
		{"name":"Mauritania","key":"MR","country-code":"478"},
		{"name":"Mauritius","key":"MU","country-code":"480"},
		{"name":"Mayotte","key":"YT","country-code":"175"},
		{"name":"Mexico","key":"MX","country-code":"484"},
		{"name":"Micronesia, Federated States of","key":"FM","country-code":"583"},
		{"name":"Moldova, Republic of","key":"MD","country-code":"498"},
		{"name":"Monaco","key":"MC","country-code":"492"},
		{"name":"Mongolia","key":"MN","country-code":"496"},
		{"name":"Montenegro","key":"ME","country-code":"499"},
		{"name":"Montserrat","key":"MS","country-code":"500"},
		{"name":"Morocco","key":"MA","country-code":"504"},
		{"name":"Mozambique","key":"MZ","country-code":"508"},
		{"name":"Myanmar","key":"MM","country-code":"104"},
		{"name":"Namibia","key":"NA","country-code":"516"},
		{"name":"Nauru","key":"NR","country-code":"520"},
		{"name":"Nepal","key":"NP","country-code":"524"},
		{"name":"Netherlands","key":"NL","country-code":"528"},
		{"name":"New Caledonia","key":"NC","country-code":"540"},
		{"name":"New Zealand","key":"NZ","country-code":"554"},
		{"name":"Nicaragua","key":"NI","country-code":"558"},
		{"name":"Niger","key":"NE","country-code":"562"},
		{"name":"Nigeria","key":"NG","country-code":"566"},
		{"name":"Niue","key":"NU","country-code":"570"},
		{"name":"Norfolk Island","key":"NF","country-code":"574"},
		{"name":"Northern Mariana Islands","key":"MP","country-code":"580"},
		{"name":"Norway","key":"NO","country-code":"578"},
		{"name":"Oman","key":"OM","country-code":"512"},
		{"name":"Pakistan","key":"PK","country-code":"586"},
		{"name":"Palau","key":"PW","country-code":"585"},
		{"name":"Palestine, State of","key":"PS","country-code":"275"},
		{"name":"Panama","key":"PA","country-code":"591"},
		{"name":"Papua New Guinea","key":"PG","country-code":"598"},
		{"name":"Paraguay","key":"PY","country-code":"600"},
		{"name":"Peru","key":"PE","country-code":"604"},
		{"name":"Philippines","key":"PH","country-code":"608"},
		{"name":"Pitcairn","key":"PN","country-code":"612"},
		{"name":"Poland","key":"PL","country-code":"616"},
		{"name":"Portugal","key":"PT","country-code":"620"},
		{"name":"Puerto Rico","key":"PR","country-code":"630"},
		{"name":"Qatar","key":"QA","country-code":"634"},
		{"name":"Réunion","key":"RE","country-code":"638"},
		{"name":"Romania","key":"RO","country-code":"642"},
		{"name":"Russian Federation","key":"RU","country-code":"643"},
		{"name":"Rwanda","key":"RW","country-code":"646"},
		{"name":"Saint Barthélemy","key":"BL","country-code":"652"},
		{"name":"Saint Helena, Ascension and Tristan da Cunha","key":"SH","country-code":"654"},
		{"name":"Saint Kitts and Nevis","key":"KN","country-code":"659"},
		{"name":"Saint Lucia","key":"LC","country-code":"662"},
		{"name":"Saint Martin (French part)","key":"MF","country-code":"663"},
		{"name":"Saint Pierre and Miquelon","key":"PM","country-code":"666"},
		{"name":"Saint Vincent and the Grenadines","key":"VC","country-code":"670"},
		{"name":"Samoa","key":"WS","country-code":"882"},
		{"name":"San Marino","key":"SM","country-code":"674"},
		{"name":"Sao Tome and Principe","key":"ST","country-code":"678"},
		{"name":"Saudi Arabia","key":"SA","country-code":"682"},
		{"name":"Senegal","key":"SN","country-code":"686"},
		{"name":"Serbia","key":"RS","country-code":"688"},
		{"name":"Seychelles","key":"SC","country-code":"690"},
		{"name":"Sierra Leone","key":"SL","country-code":"694"},
		{"name":"Singapore","key":"SG","country-code":"702"},
		{"name":"Sint Maarten (Dutch part)","key":"SX","country-code":"534"},
		{"name":"Slovakia","key":"SK","country-code":"703"},
		{"name":"Slovenia","key":"SI","country-code":"705"},
		{"name":"Solomon Islands","key":"SB","country-code":"090"},
		{"name":"Somalia","key":"SO","country-code":"706"},
		{"name":"South Africa","key":"ZA","country-code":"710"},
		{"name":"South Georgia and the South Sandwich Islands","key":"GS","country-code":"239"},
		{"name":"South Sudan","key":"SS","country-code":"728"},
		{"name":"Spain","key":"ES","country-code":"724"},
		{"name":"Sri Lanka","key":"LK","country-code":"144"},
		{"name":"Sudan","key":"SD","country-code":"729"},
		{"name":"Suriname","key":"SR","country-code":"740"},
		{"name":"Svalbard and Jan Mayen","key":"SJ","country-code":"744"},
		{"name":"Swaziland","key":"SZ","country-code":"748"},
		{"name":"Sweden","key":"SE","country-code":"752"},
		{"name":"Switzerland","key":"CH","country-code":"756"},
		{"name":"Syrian Arab Republic","key":"SY","country-code":"760"},
		{"name":"Taiwan, Province of China","key":"TW","country-code":"158"},
		{"name":"Tajikistan","key":"TJ","country-code":"762"},
		{"name":"Tanzania, United Republic of","key":"TZ","country-code":"834"},
		{"name":"Thailand","key":"TH","country-code":"764"},
		{"name":"Timor-Leste","key":"TL","country-code":"626"},
		{"name":"Togo","key":"TG","country-code":"768"},
		{"name":"Tokelau","key":"TK","country-code":"772"},
		{"name":"Tonga","key":"TO","country-code":"776"},
		{"name":"Trinidad and Tobago","key":"TT","country-code":"780"},
		{"name":"Tunisia","key":"TN","country-code":"788"},
		{"name":"Turkey","key":"TR","country-code":"792"},
		{"name":"Turkmenistan","key":"TM","country-code":"795"},
		{"name":"Turks and Caicos Islands","key":"TC","country-code":"796"},
		{"name":"Tuvalu","key":"TV","country-code":"798"},
		{"name":"Uganda","key":"UG","country-code":"800"},
		{"name":"Ukraine","key":"UA","country-code":"804"},
		{"name":"United Arab Emirates","key":"AE","country-code":"784"},
		{"name":"United Kingdom","key":"GB","country-code":"826"},
		{"name":"United States","key":"US","country-code":"840"},
		{"name":"United States Minor Outlying Islands","key":"UM","country-code":"581"},
		{"name":"Uruguay","key":"UY","country-code":"858"},
		{"name":"Uzbekistan","key":"UZ","country-code":"860"},
		{"name":"Vanuatu","key":"VU","country-code":"548"},
		{"name":"Venezuela, Bolivarian Republic of","key":"VE","country-code":"862"},
		{"name":"Viet Nam","key":"VN","country-code":"704"},
		{"name":"Virgin Islands, British","key":"VG","country-code":"092"},
		{"name":"Virgin Islands, U.S.","key":"VI","country-code":"850"},
		{"name":"Wallis and Futuna","key":"WF","country-code":"876"},
		{"name":"Western Sahara","key":"EH","country-code":"732"},
		{"name":"Yemen","key":"YE","country-code":"887"},
		{"name":"Zambia","key":"ZM","country-code":"894"},
		{"name":"Zimbabwe","key":"ZW","country-code":"716"}
	]
]


.factory('taskStorage', ->
	STORAGE_ID = 'tasks'
	DEMO_TASKS = '[
		{"title": "Finish homework", "completed": true},
		{"title": "Make a call", "completed": true},
		{"title": "Build a snowman!", "completed": false},
		{"title": "Tango! Tango! Tango!", "completed": false},
		{"title": "Play games with friends", "completed": false},
		{"title": "Shopping", "completed": false}

	]'

	return {
		get: ->
			JSON.parse(localStorage.getItem(STORAGE_ID) || DEMO_TASKS )

		put: (tasks)->
			localStorage.setItem(STORAGE_ID, JSON.stringify(tasks))
	}
)

.factory("uniqueIdService",[->
	priv =
		maxTries: 5
		defaultLen: 5
		history: {}
		generate: (len) ->
			id = ""
			possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"
			i = 0

			while i < len
				id += possible.charAt(Math.floor(Math.random() * possible.length))
				i++
			id

	exports = generate: (len) ->
		len = priv.defaultLen  unless len
		id = undefined
		tries = 0
		loop
			id = priv.generate(len)
			tries++
			break unless priv.history.hasOwnProperty(id) and tries < priv.maxTries
		throw new Error("uniqueIdService unable generate a unique ID.")  if tries > priv.maxTries
		priv.history[id] = true
		id

	exports
])

.factory "searchResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		employee: (params) ->
			$http.get DOMAIN + "/api/search/employee", params: params

		users: (params) ->
			$http.get DOMAIN + "/api/search/users", params: params

		attachments: (params) ->
			$http.get DOMAIN + "/api/search/users/attachments", params: params

		usersAutocomplete: (params) ->
			$http.get DOMAIN + "/api/search/users/autocomplete", params: params
]

.factory "attachmentsResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/search/users/attachments", params: params

		get: (id, params) ->
			$http.get DOMAIN + "/api/upload/" + id, params: params

		update: (id, params) ->
			$http.put DOMAIN + "/api/upload/" + id, params

		delete: (id) ->
			$http.delete DOMAIN + "/api/upload/" + id
]

.factory("securityService",[
	"userResource"
	"$http"
	"$cookieStore"
	"$rootScope"
	"SESSION_COOKIE_NAME"
	(userResource, $http, $cookieStore, $rootScope, SESSION_COOKIE_NAME) ->
		priv =
			# session object
			session: null
			# session object
			currentUser: null
			requestSent: false

		exports =
			init: (session) ->
				priv.requestSent = false
				# if no session, try to get from cookie
				unless session
					session = angular.fromJson($cookieStore.get(SESSION_COOKIE_NAME)) or null  if $cookieStore.get(SESSION_COOKIE_NAME)
				else
					$cookieStore.put SESSION_COOKIE_NAME, angular.toJson(session)
				priv.session = session
				# set authorization token
				authorization = priv.session.id  if priv.session and priv.session.id
				$http.defaults.headers.common["Authorization"] = authorization
				# console.log(authorization)
				return

			#this.requestCurrentUser();
			isAuthenticated: ->
				!!priv.session or !!priv.currentUser

			getSession: ->
				priv.session

			requestCurrentUser: ->
				unless priv.requestSent
					priv.requestSent = true
					userResource.getMe().success (payload) ->
						priv.currentUser = payload
						$rootScope.$broadcast "authChange"
						return

				priv.currentUser

			setUser: (user) ->
				priv.currentUser = user
				return

			destroySession: ->
				priv.session = null
				priv.currentUser = null
				priv.requestSent = false
				$cookieStore.remove SESSION_COOKIE_NAME
				$http.defaults.headers.common["Authorization"] = ""
				$rootScope.$broadcast "authChange"
				return

		return exports
])

.factory("authResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		login: (params) ->
			$http.post DOMAIN + "/api/auth/login", params
		logout: ->
			$http.post DOMAIN + "/api/auth/logout"
])

.factory("userResource", [
	"$http"
	"uniqueIdService"
	"DOMAIN"
	($http, uniqueIdService, DOMAIN) ->
		defaults:
			group_id: 3
			role_id: 6
			status: "pending"
			credential:
				email: ""
				password: uniqueIdService.generate()
			meta:
				first_name: ""
				last_name: ""
				city: ""
			register: true

		create: (params) ->
			$http.post DOMAIN + "/api/users", params
		getMe: ->
			$http.get DOMAIN + "/api/users/me"
		get: (id, params) ->
			$http.get DOMAIN + "/api/users/" + id, params: params
		updateMe: (params) ->
			$http.put DOMAIN + "/api/users/me", params
		update: (id, params) ->
			$http.put DOMAIN + "/api/users/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/users/" + id

])


.factory "refererResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		defaults:
			name: ""
			description: ""
			status: "pending"

		search: (params) ->
			$http.get DOMAIN + "/api/referer/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/referer", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/referer/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/referer/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/referer/" + id

]

.factory "bondResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		bondtype: (params) ->
			$http.get DOMAIN + "/api/bond/bondtype", params: params
		search: (params) ->
			$http.get DOMAIN + "/api/bond/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/bond", params
		add: (params) ->
			$http.post DOMAIN + "/api/bond/add", params
		remove: (id) ->
			$http.delete DOMAIN + "/api/bond/remove/" + id
		get: (id, params) ->
			$http.get DOMAIN + "/api/bond/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/bond/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/bond/" + id

]

.factory "timelineResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/timeline/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/timeline", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/timeline/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/timeline/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/timeline/" + id

]


.factory "treatmentResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/treatment/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/treatment", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/treatment/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/treatment/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/treatment/" + id
]

.factory "prescriptionResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/prescription/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/prescription", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/prescription/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/prescription/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/prescription/" + id
]

.factory "pathologyResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/pathologie/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/pathologie", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/pathologie/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/pathologie/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/pathologie/" + id
]

.factory "reviewResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		defaults:
			description: ""
		search: (params) ->
			$http.get DOMAIN + "/api/review/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/review", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/review/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/review/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/review/" + id
]

.factory "diagnosticResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		defaults:
			name: ""
			description: ""
		search: (params) ->
			$http.get DOMAIN + "/api/diagnostic/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/diagnostic", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/diagnostic/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/diagnostic/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/diagnostic/" + id

]

.factory "roomResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		defaults:
			name: ""
			description: ""
		search: (params) ->
			$http.get DOMAIN + "/api/room/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/room", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/room/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/room/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/room/" + id
]

.factory "newsResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		defaults:
			title: ""
			description: ""
		search: (params) ->
			$http.get DOMAIN + "/api/news/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/news", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/news/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/news/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/news/" + id
]


.factory "providerResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/provider/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/provider", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/provider/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/provider/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/provider/" + id
]


.factory "invoiceResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/invoice/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/invoice", params
		sendInvoice: (params) ->
			$http.post DOMAIN + "/api/invoice/send", params
		sendAmendments: (params) ->
			$http.post DOMAIN + "/api/invoice/amendments", params
		getInvoice: (params) ->
			$http.get DOMAIN + "/api/invoice/sent", params: params
		getProvider: (params) ->
			$http.get DOMAIN + "/api/invoice/provider", params: params
		getAmendments: (params) ->
			$http.get DOMAIN + "/api/invoice/amendments", params: params
		getReceive: (params) ->
			$http.get DOMAIN + "/api/invoice/receive", params: params
		get: (id, params) ->
			$http.get DOMAIN + "/api/invoice/" + id, params: params
		removeAmendments: (id, params) ->
			$http.put DOMAIN + "/api/invoice/amendments/" + id, params
		update: (id, params) ->
			$http.put DOMAIN + "/api/invoice/" + id, params
		deleteInvoice: (id) ->
			$http.delete DOMAIN + "/api/invoice/sent/" + id
		delete: (id) ->
			$http.delete DOMAIN + "/api/invoice/" + id
]

.factory "sessionResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/session/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/session", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/session/" + id, params: params
		status: (params) ->
			$http.post DOMAIN + "/api/session/status", params
		update: (id, params) ->
			$http.put DOMAIN + "/api/session/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/session/" + id
]

.factory "groupResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/group/search", params: params
		all: (params) ->
			$http.get DOMAIN + "/api/group/all", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/group", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/group/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/group/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/group/" + id
]

.factory "roleResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/role/search", params: params
		all: (params) ->
			$http.get DOMAIN + "/api/role/all", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/role", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/role/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/role/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/role/" + id
]



.factory "movementsResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/movements/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/movements", params
		spending: (params) ->
			$http.post DOMAIN + "/api/movements/spending", params
		devolution: (params) ->
			$http.post DOMAIN + "/api/movements/devolution", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/movements/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/movements/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/movements/" + id
]

.factory "messageResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		search: (params) ->
			$http.get DOMAIN + "/api/message/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/message", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/message/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/message/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/message/" + id
]

.factory "tagsResource", [
	"$http"
	"DOMAIN"
	($http, DOMAIN) ->
		defaults:
			title: ""
			description: ""
		search: (params) ->
			$http.get DOMAIN + "/api/tags/search", params: params
		create: (params) ->
			$http.post DOMAIN + "/api/tags", params
		get: (id, params) ->
			$http.get DOMAIN + "/api/tags/" + id, params: params
		update: (id, params) ->
			$http.put DOMAIN + "/api/tags/" + id, params
		delete: (id) ->
			$http.delete DOMAIN + "/api/tags/" + id
]