angular.module("App.filters", [])

.filter "unsafe", [
	"$sce"
	($sce) ->
		return (val) ->
			$sce.trustAsHtml val
]

.filter "getAvatar", ->
	(a) ->
		a = "img/avatar.jpg"  unless a
		a

.filter "percent", ->
	(val) ->
		return "0%"  if isNaN(val)
		(if not parseFloat(val)? then "0%" else String(val * 100) + "%")

.filter "sumby", ($filter, CURRENCY) ->
	(data, key) ->
		return 0  if typeof (data) is "undefined"
		# // or typeof (key) is "undefined"
		sum = 0
		angular.forEach data, (value) ->
			sum += parseFloat(value.amount)  if (value.payment_type == key || typeof (key) is "undefined")
		$filter("currency") sum, CURRENCY, 2

.filter "totalvat", ->
	(val, vat = 0) ->
		return 0  if isNaN(val) and !parseFloat(val)
		# (if (parseFloat(vat) is 0) then parseFloat(val) else ())
		(parseFloat(val) + (parseFloat(val) * parseFloat(vat)))

.filter "devolution", ->
	(val) ->
		val = (if not val? then val else parseInt(val))
		(-1 * val)
		
.filter "age", ->
	(arg) ->
		if parseInt(arg)
			today = new Date()
			return (parseInt(today.getFullYear() - parseInt(arg)))
		arg

.filter "fixbr", ->
	(arg) ->
		arg.replace /&lt;br(.*?)\/&gt;/g, "<br />"  if arg

.filter "characters", ->
	(input, chars, breakOnWord) ->
		return input  if isNaN(chars)
		return ""  if chars <= 0
		if input and input.length >= chars
			input = input.substring(0, chars)
			unless breakOnWord
				lastspace = input.lastIndexOf(" ")
				input = input.substr(0, lastspace)  if lastspace isnt -1
			else
				input = input.substr(0, input.length - 1)  while input.charAt(input.length - 1) is " "
			return input + "..."
		input


# String(text);
.filter "plaintext", ->
	(str) ->
		(if not str? then "" else String(str).replace(/<[^>]+>/gm, ' '))

.filter "capitalize", ->
	(str) ->
		str = (if not str? then "" else String(str))
		str.charAt(0).toUpperCase() + str.slice(1)

.filter "invoice", ->
	(str) ->
		str = (if not str? then "" else "FA-" + String(str))

.filter "titleize", ->
	(str) ->
		return ""  unless str?
		str = String(str).toLowerCase()
		str.replace /(?:^|\s|-)\S/g, (c) ->
			c.toUpperCase()

.filter "words", ->
	(input, words) ->
		return input  if isNaN(words)
		return ""  if words <= 0
		if input
			inputWords = input.split(/\s+/)
			input = inputWords.slice(0, words).join(" ") + "..."  if inputWords.length > words
		input

.filter "mydate", ($filter) ->
	(str) ->
		tempdate = new Date(str.replace(/-/g, "/"))
		$filter("date") tempdate, "dd/MM/yyyy"

.filter "mydatetime", ($filter) ->
	(str) ->
		tempdate = new Date(str.replace(/-/g, "/"))
		$filter("date") tempdate, "dd/MM/yyyy HH:mm:ss"

.filter "timeago", ->

	#time: the time
	#local: compared to what time? default: now
	#raw: wheter you want in a format of "5 minutes ago", or "5 minutes"
	(time, local, raw) ->
		return "never"  unless time
		local = Date.now()  unless local
		if angular.isDate(time)
			time = time.getTime()
		else time = new Date(time).getTime()  if typeof time is "string"
		if angular.isDate(local)
			local = local.getTime()
		else local = new Date(local).getTime()  if typeof local is "string"
		return  if typeof time isnt "number" or typeof local isnt "number"
		offset = Math.abs((local - time) / 1000)
		span = []
		MINUTE = 60
		HOUR = 3600
		DAY = 86400
		WEEK = 604800
		MONTH = 2629744
		YEAR = 31556926
		DECADE = 315569260
		if offset <= MINUTE
			span = [
				""
				(if raw then "now" else "less than a minute")
			]
		else if offset < (MINUTE * 60)
			span = [
				Math.round(Math.abs(offset / MINUTE))
				"min"
			]
		else if offset < (HOUR * 24)
			span = [
				Math.round(Math.abs(offset / HOUR))
				"hr"
			]
		else if offset < (DAY * 7)
			span = [
				Math.round(Math.abs(offset / DAY))
				"day"
			]
		else if offset < (WEEK * 52)
			span = [
				Math.round(Math.abs(offset / WEEK))
				"week"
			]
		else if offset < (YEAR * 10)
			span = [
				Math.round(Math.abs(offset / YEAR))
				"year"
			]
		else if offset < (DECADE * 100)
			span = [
				Math.round(Math.abs(offset / DECADE))
				"decade"
			]
		else
			span = [
				""
				"a long time"
			]
		span[1] += (if (span[0] is 0 or span[0] > 1) then "s" else "")
		span = span.join(" ")
		return span  if raw is true
		(if (time <= local) then span + " ago" else "in " + span)