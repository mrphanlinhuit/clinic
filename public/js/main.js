(function() {
  "use strict";
  var App;

  App = angular.module("App", ["ngAnimate", "ngSanitize", "ngRoute", "ngCookies", "ngResource", "App.filters", "App.services", "App.directives", "App.controllers", "ui.bootstrap", "ui.calendar", "angularFileUpload", "ngTagsInput", "textAngular"]).constant("DOMAIN", "").constant("CURRENCY", "€ ").constant("SESSION_COOKIE_NAME", "session").constant("DELAY", 5000).constant("DEFAULT_ROUTE", "/dashboard").constant("REQUIRE_AUTH", "/user/signin").constant("PERPAGE", [10, 20, 50, 100]).constant("version", "© 2014 beesightsoft.com").config([
    "$routeProvider", "$locationProvider", "DEFAULT_ROUTE", function($routeProvider, $locationProvider, DEFAULT_ROUTE) {
      var resolve;
      $routeProvider.when("/pdf/invoice/:id", {
        templateUrl: "html/accounting/invoice.html"
      });
      $routeProvider.when("/accounting/invoice/:id", {
        templateUrl: "html/accounting/invoice.html"
      });
      $routeProvider.when("/accounting/invoices", {
        templateUrl: "html/accounting/invoices.html"
      });
      $routeProvider.when("/accounting/download", {
        templateUrl: "html/accounting/download.html"
      });
      $routeProvider.when("/accounting/provider-new", {
        templateUrl: "html/accounting/provider-new.html"
      });
      $routeProvider.when("/accounting/provider/:id", {
        templateUrl: "html/accounting/provider.html"
      });
      $routeProvider.when("/accounting/providers", {
        templateUrl: "html/accounting/providers.html"
      });
      $routeProvider.when("/accounting/provider-list", {
        templateUrl: "html/accounting/provider-list.html"
      });
      $routeProvider.when("/accounting/provider-invoices", {
        templateUrl: "html/accounting/provider-invoices.html"
      });
      $routeProvider.when("/accounting/statistics", {
        templateUrl: "html/accounting/statistics.html"
      });
      $routeProvider.when("/administration", {
        templateUrl: "html/administration/administration.html"
      });
      $routeProvider.when("/administration/bonds", {
        templateUrl: "html/administration/bonds.html"
      });
      $routeProvider.when("/administration/clinic", {
        templateUrl: "html/administration/clinic.html"
      });
      $routeProvider.when("/administration/pathologies", {
        templateUrl: "html/administration/pathologies.html"
      });
      $routeProvider.when("/administration/referer", {
        templateUrl: "html/administration/referer.html"
      });
      $routeProvider.when("/administration/referer/:id", {
        templateUrl: "html/administration/referer.html"
      });
      $routeProvider.when("/administration/referers", {
        templateUrl: "html/administration/referers.html"
      });
      $routeProvider.when("/administration/rooms", {
        templateUrl: "html/administration/rooms.html"
      });
      $routeProvider.when("/administration/room", {
        templateUrl: "html/administration/room.html"
      });
      $routeProvider.when("/administration/room/:id", {
        templateUrl: "html/administration/room.html"
      });
      $routeProvider.when("/administration/treatments", {
        templateUrl: "html/administration/treatments.html"
      });
      $routeProvider.when("/administration/news", {
        templateUrl: "html/administration/news.html",
        controller: "NewsCtrl"
      });
      $routeProvider.when("/cash/movements", {
        templateUrl: "html/cash/movements.html"
      });
      $routeProvider.when("/cash/statistics", {
        templateUrl: "html/cash/statistics.html"
      });
      $routeProvider.when("/calendar", {
        templateUrl: "html/calendar/index.html"
      });
      $routeProvider.when(DEFAULT_ROUTE, {
        templateUrl: "html/dashboard.html",
        controller: "DashboardCtrl",
        resolve: resolve = {
          user: function(securityService) {
            return securityService.requestCurrentUser();
          }
        }
      });
      $routeProvider.when("/user/list", {
        templateUrl: "html/user/list.html"
      });
      $routeProvider.when("/user/signout", {
        controller: "SignoutCtrl",
        resolve: resolve = {
          logout: function($location, securityService) {
            securityService.destroySession();
            $location.path("/user/signin");
          }
        }
      });
      $routeProvider.when("/user/signin", {
        templateUrl: "html/user/signin.html",
        controller: "SigninCtrl"
      });
      $routeProvider.when("/user/password", {
        templateUrl: "html/user/password.html",
        controller: "PasswordCtrl"
      });
      $routeProvider.when("/user/lock", {
        templateUrl: "html/lock.html"
      });
      $routeProvider.when("/user/forgot", {
        templateUrl: "html/user/forgot.html"
      });
      $routeProvider.when("/messages", {
        templateUrl: "html/user/messages.html"
      });
      $routeProvider.when("/messages/:id", {
        templateUrl: "html/user/message.html"
      });
      $routeProvider.when("/user/:id", {
        templateUrl: "html/user/user.html"
      });
      $routeProvider.when("/patient/list", {
        templateUrl: "html/patient/list.html"
      });
      $routeProvider.when("/patient/invoice/:id", {
        templateUrl: "html/patient/invoice.html"
      });
      $routeProvider.when("/patient/cash/:id", {
        templateUrl: "html/patient/cash.html"
      });
      $routeProvider.when("/patient/invoices/:id", {
        templateUrl: "html/patient/invoices.html"
      });
      $routeProvider.when("/patient/timeline/:id", {
        templateUrl: "html/patient/timeline.html"
      });
      $routeProvider.when("/patient/appointments/:id", {
        templateUrl: "html/patient/appointments.html"
      });
      $routeProvider.when("/patient/bonds/:id", {
        templateUrl: "html/patient/bonds.html"
      });
      $routeProvider.when("/patient/diagnostic", {
        templateUrl: "html/patient/diagnostic.html"
      });
      $routeProvider.when("/patient/diagnostic/:id", {
        templateUrl: "html/patient/diagnostic.html"
      });
      $routeProvider.when("/patient/diagnostics/:id", {
        templateUrl: "html/patient/diagnostics.html"
      });
      $routeProvider.when("/patient/:id", {
        templateUrl: "html/patient/patient.html"
      });
      $routeProvider.otherwise({
        redirectTo: DEFAULT_ROUTE
      });
      return $locationProvider.html5Mode(false);
    }
  ]).config([
    "$httpProvider", function($httpProvider) {
      $httpProvider.defaults.headers.common = {
        "Accept": "application/json, text/plain, */*",
        "Content-Type": "application/json;charset=utf-8",
        "X-Requested-With": "XMLHttpRequest"
      };
      return $httpProvider.responseInterceptors.push(function($q, $location, $rootScope, DEFAULT_ROUTE) {
        return function(promise) {
          return promise.then((function(response) {
            var currentPath, payloadData, ref;
            if (response.headers()["content-type"] === "application/json; charset=utf-8" || response.headers()["content-type"] === "application/json") {
              if (response.data.code === 200 || response.data.code === 302) {
                payloadData = response.data.payload;
                response.data = payloadData;
                return response;
              } else {
                if (response.data.code === 400) {
                  $rootScope.$broadcast("error", response.data.message || "Error. Bad Request.");
                } else if (response.data.code === 401) {
                  currentPath = $location.path();
                  if (currentPath !== "/user/signout" && $location.path() !== "/user/signin" && $location.path() !== "/user/signup") {
                    ref = $location.$$url;
                    $location.path("/user/signout").search({
                      ref: ref
                    });
                  }
                } else if (response.data.code === 403) {
                  if ($location.path() !== DEFAULT_ROUTE) {
                    $rootScope.$broadcast("error", response.data.message || "Error. Forbidden.");
                  }
                } else if (response.data.code === 404) {
                  if ($location.path() !== DEFAULT_ROUTE) {
                    $rootScope.$broadcast("error", response.data.message || "Error. Not Found.");
                  }
                } else {
                  $rootScope.$broadcast("error", response.data.message || "Error. Server is having a problem");
                }
                return $q.reject(response);
              }
            }
            return response;
          }), function(response) {
            return $q.reject(response);
          });
        };
      });
    }
  ]).config([
    "$compileProvider", function($compileProvider) {
      return $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|mailto|tel|sms):/);
    }
  ]).run([
    "$rootScope", "$location", "securityService", "REQUIRE_AUTH", function($rootScope, $location, securityService, REQUIRE_AUTH) {
      var skipAuth;
      skipAuth = ["/user/signin", "/user/signout", "/user/lock"];
      $rootScope.isPath = null;
      securityService.init();
      return $rootScope.$on("$routeChangeStart", function(event, next, current) {
        var isAuthenticated, query;
        isAuthenticated = void 0;
        query = void 0;
        $rootScope.isPath = $location.path();
        isAuthenticated = securityService.isAuthenticated();
        if (isAuthenticated) {
          query = $location.search();
          if (query.ref) {
            $location.search({}).path(query.ref);
          }
        } else {
          if (!_(skipAuth).contains($rootScope.isPath)) {
            $location.path(REQUIRE_AUTH);
          }
        }
      });
    }
  ]);

}).call(this);

//# sourceMappingURL=app.js.map

;
(function() {
  angular.module("App.services", []).factory("Session", function() {
    return {
      get: function(key) {
        var record;
        record = JSON.parse(sessionStorage.getItem(key));
        if (!record) {
          return false;
        }
        return new Date().getTime() < record.timestamp && JSON.parse(record.value);
      },
      set: function(key, val, time) {
        var expire, record;
        if (time == null) {
          time = 864000;
        }
        expire = time * 60 * 1000;
        record = {
          value: JSON.stringify(val),
          timestamp: new Date().getTime() + expire
        };
        sessionStorage.setItem(key, JSON.stringify(record));
        return val;
      },
      unset: function(key) {
        return sessionStorage.removeItem(key);
      }
    };
  }).factory("GenderData", [
    function() {
      return [
        {
          key: "female",
          label: "Female"
        }, {
          key: "male",
          label: "Male"
        }
      ];
    }
  ]).factory("StatusData", [
    function() {
      return [
        {
          key: "pending",
          label: "pending"
        }, {
          key: "active",
          label: "active"
        }, {
          key: "inactive",
          label: "inactive"
        }, {
          key: "banned",
          label: "banned"
        }
      ];
    }
  ]).factory("PostalData", [
    function() {
      return [
        {
          "key": "01",
          "name": "Alava"
        }, {
          "key": "02",
          "name": "Albacete"
        }, {
          "key": "03",
          "name": "Alicante"
        }, {
          "key": "04",
          "name": "Almería"
        }, {
          "key": "05",
          "name": "Avila"
        }, {
          "key": "06",
          "name": "Badajoz"
        }, {
          "key": "07",
          "name": "Illes Balears"
        }, {
          "key": "07",
          "name": "Islas Baleares"
        }, {
          "key": "08",
          "name": "Barcelona"
        }, {
          "key": "09",
          "name": "Burgos"
        }, {
          "key": "10",
          "name": "Cáceres"
        }, {
          "key": "11",
          "name": "Cádiz"
        }, {
          "key": "12",
          "name": "Castellón"
        }, {
          "key": "13",
          "name": "Ciudad Real"
        }, {
          "key": "14",
          "name": "Córdoba"
        }, {
          "key": "15",
          "name": "La Coruña"
        }, {
          "key": "16",
          "name": "Cuenca"
        }, {
          "key": "17",
          "name": "Gerona"
        }, {
          "key": "18",
          "name": "Granada"
        }, {
          "key": "19",
          "name": "Guadalajara"
        }, {
          "key": "20",
          "name": "Guipuzcoa"
        }, {
          "key": "21",
          "name": "Huelva"
        }, {
          "key": "22",
          "name": "Huesca"
        }, {
          "key": "23",
          "name": "Jaen"
        }, {
          "key": "24",
          "name": "León"
        }, {
          "key": "25",
          "name": "Lérida"
        }, {
          "key": "26",
          "name": "La Rioja"
        }, {
          "key": "27",
          "name": "Lugo"
        }, {
          "key": "28",
          "name": "Madrid"
        }, {
          "key": "29",
          "name": "Málaga"
        }, {
          "key": "30",
          "name": "Murcia"
        }, {
          "key": "31",
          "name": "Navarra"
        }, {
          "key": "32",
          "name": "Orense"
        }, {
          "key": "33",
          "name": "Asturias"
        }, {
          "key": "34",
          "name": "Palencia"
        }, {
          "key": "35",
          "name": "Las Palmas"
        }, {
          "key": "36",
          "name": "Pontevedra"
        }, {
          "key": "37",
          "name": "Salamanca"
        }, {
          "key": "38",
          "name": "S.C.Tenerife"
        }, {
          "key": "39",
          "name": "Cantabria"
        }, {
          "key": "40",
          "name": "Segovia"
        }, {
          "key": "41",
          "name": "Sevilla"
        }, {
          "key": "42",
          "name": "Soria"
        }, {
          "key": "43",
          "name": "Tarragona"
        }, {
          "key": "44",
          "name": "Teruel"
        }, {
          "key": "45",
          "name": "Toledo"
        }, {
          "key": "46",
          "name": "Valencia"
        }, {
          "key": "47",
          "name": "Valladolid"
        }, {
          "key": "48",
          "name": "Vizcaya"
        }, {
          "key": "49",
          "name": "Zamora"
        }, {
          "key": "50",
          "name": "Zaragoza"
        }, {
          "key": "51",
          "name": "Ceuta"
        }, {
          "key": "52",
          "name": "Melilla"
        }
      ];
    }
  ]).factory("CountryData", [
    function() {
      return [
        {
          "name": "Afghanistan",
          "key": "AF",
          "country-code": "004"
        }, {
          "name": "Åland Islands",
          "key": "AX",
          "country-code": "248"
        }, {
          "name": "Albania",
          "key": "AL",
          "country-code": "008"
        }, {
          "name": "Algeria",
          "key": "DZ",
          "country-code": "012"
        }, {
          "name": "American Samoa",
          "key": "AS",
          "country-code": "016"
        }, {
          "name": "Andorra",
          "key": "AD",
          "country-code": "020"
        }, {
          "name": "Angola",
          "key": "AO",
          "country-code": "024"
        }, {
          "name": "Anguilla",
          "key": "AI",
          "country-code": "660"
        }, {
          "name": "Antarctica",
          "key": "AQ",
          "country-code": "010"
        }, {
          "name": "Antigua and Barbuda",
          "key": "AG",
          "country-code": "028"
        }, {
          "name": "Argentina",
          "key": "AR",
          "country-code": "032"
        }, {
          "name": "Armenia",
          "key": "AM",
          "country-code": "051"
        }, {
          "name": "Aruba",
          "key": "AW",
          "country-code": "533"
        }, {
          "name": "Australia",
          "key": "AU",
          "country-code": "036"
        }, {
          "name": "Austria",
          "key": "AT",
          "country-code": "040"
        }, {
          "name": "Azerbaijan",
          "key": "AZ",
          "country-code": "031"
        }, {
          "name": "Bahamas",
          "key": "BS",
          "country-code": "044"
        }, {
          "name": "Bahrain",
          "key": "BH",
          "country-code": "048"
        }, {
          "name": "Bangladesh",
          "key": "BD",
          "country-code": "050"
        }, {
          "name": "Barbados",
          "key": "BB",
          "country-code": "052"
        }, {
          "name": "Belarus",
          "key": "BY",
          "country-code": "112"
        }, {
          "name": "Belgium",
          "key": "BE",
          "country-code": "056"
        }, {
          "name": "Belize",
          "key": "BZ",
          "country-code": "084"
        }, {
          "name": "Benin",
          "key": "BJ",
          "country-code": "204"
        }, {
          "name": "Bermuda",
          "key": "BM",
          "country-code": "060"
        }, {
          "name": "Bhutan",
          "key": "BT",
          "country-code": "064"
        }, {
          "name": "Bolivia, Plurinational State of",
          "key": "BO",
          "country-code": "068"
        }, {
          "name": "Bonaire, Sint Eustatius and Saba",
          "key": "BQ",
          "country-code": "535"
        }, {
          "name": "Bosnia and Herzegovina",
          "key": "BA",
          "country-code": "070"
        }, {
          "name": "Botswana",
          "key": "BW",
          "country-code": "072"
        }, {
          "name": "Bouvet Island",
          "key": "BV",
          "country-code": "074"
        }, {
          "name": "Brazil",
          "key": "BR",
          "country-code": "076"
        }, {
          "name": "British Indian Ocean Territory",
          "key": "IO",
          "country-code": "086"
        }, {
          "name": "Brunei Darussalam",
          "key": "BN",
          "country-code": "096"
        }, {
          "name": "Bulgaria",
          "key": "BG",
          "country-code": "100"
        }, {
          "name": "Burkina Faso",
          "key": "BF",
          "country-code": "854"
        }, {
          "name": "Burundi",
          "key": "BI",
          "country-code": "108"
        }, {
          "name": "Cambodia",
          "key": "KH",
          "country-code": "116"
        }, {
          "name": "Cameroon",
          "key": "CM",
          "country-code": "120"
        }, {
          "name": "Canada",
          "key": "CA",
          "country-code": "124"
        }, {
          "name": "Cape Verde",
          "key": "CV",
          "country-code": "132"
        }, {
          "name": "Cayman Islands",
          "key": "KY",
          "country-code": "136"
        }, {
          "name": "Central African Republic",
          "key": "CF",
          "country-code": "140"
        }, {
          "name": "Chad",
          "key": "TD",
          "country-code": "148"
        }, {
          "name": "Chile",
          "key": "CL",
          "country-code": "152"
        }, {
          "name": "China",
          "key": "CN",
          "country-code": "156"
        }, {
          "name": "Christmas Island",
          "key": "CX",
          "country-code": "162"
        }, {
          "name": "Cocos (Keeling) Islands",
          "key": "CC",
          "country-code": "166"
        }, {
          "name": "Colombia",
          "key": "CO",
          "country-code": "170"
        }, {
          "name": "Comoros",
          "key": "KM",
          "country-code": "174"
        }, {
          "name": "Congo",
          "key": "CG",
          "country-code": "178"
        }, {
          "name": "Congo, the Democratic Republic of the",
          "key": "CD",
          "country-code": "180"
        }, {
          "name": "Cook Islands",
          "key": "CK",
          "country-code": "184"
        }, {
          "name": "Costa Rica",
          "key": "CR",
          "country-code": "188"
        }, {
          "name": "Côte d'Ivoire",
          "key": "CI",
          "country-code": "384"
        }, {
          "name": "Croatia",
          "key": "HR",
          "country-code": "191"
        }, {
          "name": "Cuba",
          "key": "CU",
          "country-code": "192"
        }, {
          "name": "Curaçao",
          "key": "CW",
          "country-code": "531"
        }, {
          "name": "Cyprus",
          "key": "CY",
          "country-code": "196"
        }, {
          "name": "Czech Republic",
          "key": "CZ",
          "country-code": "203"
        }, {
          "name": "Denmark",
          "key": "DK",
          "country-code": "208"
        }, {
          "name": "Djibouti",
          "key": "DJ",
          "country-code": "262"
        }, {
          "name": "Dominica",
          "key": "DM",
          "country-code": "212"
        }, {
          "name": "Dominican Republic",
          "key": "DO",
          "country-code": "214"
        }, {
          "name": "Ecuador",
          "key": "EC",
          "country-code": "218"
        }, {
          "name": "Egypt",
          "key": "EG",
          "country-code": "818"
        }, {
          "name": "El Salvador",
          "key": "SV",
          "country-code": "222"
        }, {
          "name": "Equatorial Guinea",
          "key": "GQ",
          "country-code": "226"
        }, {
          "name": "Eritrea",
          "key": "ER",
          "country-code": "232"
        }, {
          "name": "Estonia",
          "key": "EE",
          "country-code": "233"
        }, {
          "name": "Ethiopia",
          "key": "ET",
          "country-code": "231"
        }, {
          "name": "Falkland Islands (Malvinas)",
          "key": "FK",
          "country-code": "238"
        }, {
          "name": "Faroe Islands",
          "key": "FO",
          "country-code": "234"
        }, {
          "name": "Fiji",
          "key": "FJ",
          "country-code": "242"
        }, {
          "name": "Finland",
          "key": "FI",
          "country-code": "246"
        }, {
          "name": "France",
          "key": "FR",
          "country-code": "250"
        }, {
          "name": "French Guiana",
          "key": "GF",
          "country-code": "254"
        }, {
          "name": "French Polynesia",
          "key": "PF",
          "country-code": "258"
        }, {
          "name": "French Southern Territories",
          "key": "TF",
          "country-code": "260"
        }, {
          "name": "Gabon",
          "key": "GA",
          "country-code": "266"
        }, {
          "name": "Gambia",
          "key": "GM",
          "country-code": "270"
        }, {
          "name": "Georgia",
          "key": "GE",
          "country-code": "268"
        }, {
          "name": "Germany",
          "key": "DE",
          "country-code": "276"
        }, {
          "name": "Ghana",
          "key": "GH",
          "country-code": "288"
        }, {
          "name": "Gibraltar",
          "key": "GI",
          "country-code": "292"
        }, {
          "name": "Greece",
          "key": "GR",
          "country-code": "300"
        }, {
          "name": "Greenland",
          "key": "GL",
          "country-code": "304"
        }, {
          "name": "Grenada",
          "key": "GD",
          "country-code": "308"
        }, {
          "name": "Guadeloupe",
          "key": "GP",
          "country-code": "312"
        }, {
          "name": "Guam",
          "key": "GU",
          "country-code": "316"
        }, {
          "name": "Guatemala",
          "key": "GT",
          "country-code": "320"
        }, {
          "name": "Guernsey",
          "key": "GG",
          "country-code": "831"
        }, {
          "name": "Guinea",
          "key": "GN",
          "country-code": "324"
        }, {
          "name": "Guinea-Bissau",
          "key": "GW",
          "country-code": "624"
        }, {
          "name": "Guyana",
          "key": "GY",
          "country-code": "328"
        }, {
          "name": "Haiti",
          "key": "HT",
          "country-code": "332"
        }, {
          "name": "Heard Island and McDonald Islands",
          "key": "HM",
          "country-code": "334"
        }, {
          "name": "Holy See (Vatican City State)",
          "key": "VA",
          "country-code": "336"
        }, {
          "name": "Honduras",
          "key": "HN",
          "country-code": "340"
        }, {
          "name": "Hong Kong",
          "key": "HK",
          "country-code": "344"
        }, {
          "name": "Hungary",
          "key": "HU",
          "country-code": "348"
        }, {
          "name": "Iceland",
          "key": "IS",
          "country-code": "352"
        }, {
          "name": "India",
          "key": "IN",
          "country-code": "356"
        }, {
          "name": "Indonesia",
          "key": "ID",
          "country-code": "360"
        }, {
          "name": "Iran, Islamic Republic of",
          "key": "IR",
          "country-code": "364"
        }, {
          "name": "Iraq",
          "key": "IQ",
          "country-code": "368"
        }, {
          "name": "Ireland",
          "key": "IE",
          "country-code": "372"
        }, {
          "name": "Isle of Man",
          "key": "IM",
          "country-code": "833"
        }, {
          "name": "Israel",
          "key": "IL",
          "country-code": "376"
        }, {
          "name": "Italy",
          "key": "IT",
          "country-code": "380"
        }, {
          "name": "Jamaica",
          "key": "JM",
          "country-code": "388"
        }, {
          "name": "Japan",
          "key": "JP",
          "country-code": "392"
        }, {
          "name": "Jersey",
          "key": "JE",
          "country-code": "832"
        }, {
          "name": "Jordan",
          "key": "JO",
          "country-code": "400"
        }, {
          "name": "Kazakhstan",
          "key": "KZ",
          "country-code": "398"
        }, {
          "name": "Kenya",
          "key": "KE",
          "country-code": "404"
        }, {
          "name": "Kiribati",
          "key": "KI",
          "country-code": "296"
        }, {
          "name": "Korea, Democratic People's Republic of",
          "key": "KP",
          "country-code": "408"
        }, {
          "name": "Korea, Republic of",
          "key": "KR",
          "country-code": "410"
        }, {
          "name": "Kuwait",
          "key": "KW",
          "country-code": "414"
        }, {
          "name": "Kyrgyzstan",
          "key": "KG",
          "country-code": "417"
        }, {
          "name": "Lao People's Democratic Republic",
          "key": "LA",
          "country-code": "418"
        }, {
          "name": "Latvia",
          "key": "LV",
          "country-code": "428"
        }, {
          "name": "Lebanon",
          "key": "LB",
          "country-code": "422"
        }, {
          "name": "Lesotho",
          "key": "LS",
          "country-code": "426"
        }, {
          "name": "Liberia",
          "key": "LR",
          "country-code": "430"
        }, {
          "name": "Libya",
          "key": "LY",
          "country-code": "434"
        }, {
          "name": "Liechtenstein",
          "key": "LI",
          "country-code": "438"
        }, {
          "name": "Lithuania",
          "key": "LT",
          "country-code": "440"
        }, {
          "name": "Luxembourg",
          "key": "LU",
          "country-code": "442"
        }, {
          "name": "Macao",
          "key": "MO",
          "country-code": "446"
        }, {
          "name": "Macedonia, the former Yugoslav Republic of",
          "key": "MK",
          "country-code": "807"
        }, {
          "name": "Madagascar",
          "key": "MG",
          "country-code": "450"
        }, {
          "name": "Malawi",
          "key": "MW",
          "country-code": "454"
        }, {
          "name": "Malaysia",
          "key": "MY",
          "country-code": "458"
        }, {
          "name": "Maldives",
          "key": "MV",
          "country-code": "462"
        }, {
          "name": "Mali",
          "key": "ML",
          "country-code": "466"
        }, {
          "name": "Malta",
          "key": "MT",
          "country-code": "470"
        }, {
          "name": "Marshall Islands",
          "key": "MH",
          "country-code": "584"
        }, {
          "name": "Martinique",
          "key": "MQ",
          "country-code": "474"
        }, {
          "name": "Mauritania",
          "key": "MR",
          "country-code": "478"
        }, {
          "name": "Mauritius",
          "key": "MU",
          "country-code": "480"
        }, {
          "name": "Mayotte",
          "key": "YT",
          "country-code": "175"
        }, {
          "name": "Mexico",
          "key": "MX",
          "country-code": "484"
        }, {
          "name": "Micronesia, Federated States of",
          "key": "FM",
          "country-code": "583"
        }, {
          "name": "Moldova, Republic of",
          "key": "MD",
          "country-code": "498"
        }, {
          "name": "Monaco",
          "key": "MC",
          "country-code": "492"
        }, {
          "name": "Mongolia",
          "key": "MN",
          "country-code": "496"
        }, {
          "name": "Montenegro",
          "key": "ME",
          "country-code": "499"
        }, {
          "name": "Montserrat",
          "key": "MS",
          "country-code": "500"
        }, {
          "name": "Morocco",
          "key": "MA",
          "country-code": "504"
        }, {
          "name": "Mozambique",
          "key": "MZ",
          "country-code": "508"
        }, {
          "name": "Myanmar",
          "key": "MM",
          "country-code": "104"
        }, {
          "name": "Namibia",
          "key": "NA",
          "country-code": "516"
        }, {
          "name": "Nauru",
          "key": "NR",
          "country-code": "520"
        }, {
          "name": "Nepal",
          "key": "NP",
          "country-code": "524"
        }, {
          "name": "Netherlands",
          "key": "NL",
          "country-code": "528"
        }, {
          "name": "New Caledonia",
          "key": "NC",
          "country-code": "540"
        }, {
          "name": "New Zealand",
          "key": "NZ",
          "country-code": "554"
        }, {
          "name": "Nicaragua",
          "key": "NI",
          "country-code": "558"
        }, {
          "name": "Niger",
          "key": "NE",
          "country-code": "562"
        }, {
          "name": "Nigeria",
          "key": "NG",
          "country-code": "566"
        }, {
          "name": "Niue",
          "key": "NU",
          "country-code": "570"
        }, {
          "name": "Norfolk Island",
          "key": "NF",
          "country-code": "574"
        }, {
          "name": "Northern Mariana Islands",
          "key": "MP",
          "country-code": "580"
        }, {
          "name": "Norway",
          "key": "NO",
          "country-code": "578"
        }, {
          "name": "Oman",
          "key": "OM",
          "country-code": "512"
        }, {
          "name": "Pakistan",
          "key": "PK",
          "country-code": "586"
        }, {
          "name": "Palau",
          "key": "PW",
          "country-code": "585"
        }, {
          "name": "Palestine, State of",
          "key": "PS",
          "country-code": "275"
        }, {
          "name": "Panama",
          "key": "PA",
          "country-code": "591"
        }, {
          "name": "Papua New Guinea",
          "key": "PG",
          "country-code": "598"
        }, {
          "name": "Paraguay",
          "key": "PY",
          "country-code": "600"
        }, {
          "name": "Peru",
          "key": "PE",
          "country-code": "604"
        }, {
          "name": "Philippines",
          "key": "PH",
          "country-code": "608"
        }, {
          "name": "Pitcairn",
          "key": "PN",
          "country-code": "612"
        }, {
          "name": "Poland",
          "key": "PL",
          "country-code": "616"
        }, {
          "name": "Portugal",
          "key": "PT",
          "country-code": "620"
        }, {
          "name": "Puerto Rico",
          "key": "PR",
          "country-code": "630"
        }, {
          "name": "Qatar",
          "key": "QA",
          "country-code": "634"
        }, {
          "name": "Réunion",
          "key": "RE",
          "country-code": "638"
        }, {
          "name": "Romania",
          "key": "RO",
          "country-code": "642"
        }, {
          "name": "Russian Federation",
          "key": "RU",
          "country-code": "643"
        }, {
          "name": "Rwanda",
          "key": "RW",
          "country-code": "646"
        }, {
          "name": "Saint Barthélemy",
          "key": "BL",
          "country-code": "652"
        }, {
          "name": "Saint Helena, Ascension and Tristan da Cunha",
          "key": "SH",
          "country-code": "654"
        }, {
          "name": "Saint Kitts and Nevis",
          "key": "KN",
          "country-code": "659"
        }, {
          "name": "Saint Lucia",
          "key": "LC",
          "country-code": "662"
        }, {
          "name": "Saint Martin (French part)",
          "key": "MF",
          "country-code": "663"
        }, {
          "name": "Saint Pierre and Miquelon",
          "key": "PM",
          "country-code": "666"
        }, {
          "name": "Saint Vincent and the Grenadines",
          "key": "VC",
          "country-code": "670"
        }, {
          "name": "Samoa",
          "key": "WS",
          "country-code": "882"
        }, {
          "name": "San Marino",
          "key": "SM",
          "country-code": "674"
        }, {
          "name": "Sao Tome and Principe",
          "key": "ST",
          "country-code": "678"
        }, {
          "name": "Saudi Arabia",
          "key": "SA",
          "country-code": "682"
        }, {
          "name": "Senegal",
          "key": "SN",
          "country-code": "686"
        }, {
          "name": "Serbia",
          "key": "RS",
          "country-code": "688"
        }, {
          "name": "Seychelles",
          "key": "SC",
          "country-code": "690"
        }, {
          "name": "Sierra Leone",
          "key": "SL",
          "country-code": "694"
        }, {
          "name": "Singapore",
          "key": "SG",
          "country-code": "702"
        }, {
          "name": "Sint Maarten (Dutch part)",
          "key": "SX",
          "country-code": "534"
        }, {
          "name": "Slovakia",
          "key": "SK",
          "country-code": "703"
        }, {
          "name": "Slovenia",
          "key": "SI",
          "country-code": "705"
        }, {
          "name": "Solomon Islands",
          "key": "SB",
          "country-code": "090"
        }, {
          "name": "Somalia",
          "key": "SO",
          "country-code": "706"
        }, {
          "name": "South Africa",
          "key": "ZA",
          "country-code": "710"
        }, {
          "name": "South Georgia and the South Sandwich Islands",
          "key": "GS",
          "country-code": "239"
        }, {
          "name": "South Sudan",
          "key": "SS",
          "country-code": "728"
        }, {
          "name": "Spain",
          "key": "ES",
          "country-code": "724"
        }, {
          "name": "Sri Lanka",
          "key": "LK",
          "country-code": "144"
        }, {
          "name": "Sudan",
          "key": "SD",
          "country-code": "729"
        }, {
          "name": "Suriname",
          "key": "SR",
          "country-code": "740"
        }, {
          "name": "Svalbard and Jan Mayen",
          "key": "SJ",
          "country-code": "744"
        }, {
          "name": "Swaziland",
          "key": "SZ",
          "country-code": "748"
        }, {
          "name": "Sweden",
          "key": "SE",
          "country-code": "752"
        }, {
          "name": "Switzerland",
          "key": "CH",
          "country-code": "756"
        }, {
          "name": "Syrian Arab Republic",
          "key": "SY",
          "country-code": "760"
        }, {
          "name": "Taiwan, Province of China",
          "key": "TW",
          "country-code": "158"
        }, {
          "name": "Tajikistan",
          "key": "TJ",
          "country-code": "762"
        }, {
          "name": "Tanzania, United Republic of",
          "key": "TZ",
          "country-code": "834"
        }, {
          "name": "Thailand",
          "key": "TH",
          "country-code": "764"
        }, {
          "name": "Timor-Leste",
          "key": "TL",
          "country-code": "626"
        }, {
          "name": "Togo",
          "key": "TG",
          "country-code": "768"
        }, {
          "name": "Tokelau",
          "key": "TK",
          "country-code": "772"
        }, {
          "name": "Tonga",
          "key": "TO",
          "country-code": "776"
        }, {
          "name": "Trinidad and Tobago",
          "key": "TT",
          "country-code": "780"
        }, {
          "name": "Tunisia",
          "key": "TN",
          "country-code": "788"
        }, {
          "name": "Turkey",
          "key": "TR",
          "country-code": "792"
        }, {
          "name": "Turkmenistan",
          "key": "TM",
          "country-code": "795"
        }, {
          "name": "Turks and Caicos Islands",
          "key": "TC",
          "country-code": "796"
        }, {
          "name": "Tuvalu",
          "key": "TV",
          "country-code": "798"
        }, {
          "name": "Uganda",
          "key": "UG",
          "country-code": "800"
        }, {
          "name": "Ukraine",
          "key": "UA",
          "country-code": "804"
        }, {
          "name": "United Arab Emirates",
          "key": "AE",
          "country-code": "784"
        }, {
          "name": "United Kingdom",
          "key": "GB",
          "country-code": "826"
        }, {
          "name": "United States",
          "key": "US",
          "country-code": "840"
        }, {
          "name": "United States Minor Outlying Islands",
          "key": "UM",
          "country-code": "581"
        }, {
          "name": "Uruguay",
          "key": "UY",
          "country-code": "858"
        }, {
          "name": "Uzbekistan",
          "key": "UZ",
          "country-code": "860"
        }, {
          "name": "Vanuatu",
          "key": "VU",
          "country-code": "548"
        }, {
          "name": "Venezuela, Bolivarian Republic of",
          "key": "VE",
          "country-code": "862"
        }, {
          "name": "Viet Nam",
          "key": "VN",
          "country-code": "704"
        }, {
          "name": "Virgin Islands, British",
          "key": "VG",
          "country-code": "092"
        }, {
          "name": "Virgin Islands, U.S.",
          "key": "VI",
          "country-code": "850"
        }, {
          "name": "Wallis and Futuna",
          "key": "WF",
          "country-code": "876"
        }, {
          "name": "Western Sahara",
          "key": "EH",
          "country-code": "732"
        }, {
          "name": "Yemen",
          "key": "YE",
          "country-code": "887"
        }, {
          "name": "Zambia",
          "key": "ZM",
          "country-code": "894"
        }, {
          "name": "Zimbabwe",
          "key": "ZW",
          "country-code": "716"
        }
      ];
    }
  ]).factory('taskStorage', function() {
    var DEMO_TASKS, STORAGE_ID;
    STORAGE_ID = 'tasks';
    DEMO_TASKS = '[ {"title": "Finish homework", "completed": true}, {"title": "Make a call", "completed": true}, {"title": "Build a snowman!", "completed": false}, {"title": "Tango! Tango! Tango!", "completed": false}, {"title": "Play games with friends", "completed": false}, {"title": "Shopping", "completed": false} ]';
    return {
      get: function() {
        return JSON.parse(localStorage.getItem(STORAGE_ID) || DEMO_TASKS);
      },
      put: function(tasks) {
        return localStorage.setItem(STORAGE_ID, JSON.stringify(tasks));
      }
    };
  }).factory("uniqueIdService", [
    function() {
      var exports, priv;
      priv = {
        maxTries: 5,
        defaultLen: 5,
        history: {},
        generate: function(len) {
          var i, id, possible;
          id = "";
          possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
          i = 0;
          while (i < len) {
            id += possible.charAt(Math.floor(Math.random() * possible.length));
            i++;
          }
          return id;
        }
      };
      exports = {
        generate: function(len) {
          var id, tries;
          if (!len) {
            len = priv.defaultLen;
          }
          id = void 0;
          tries = 0;
          while (true) {
            id = priv.generate(len);
            tries++;
            if (!(priv.history.hasOwnProperty(id) && tries < priv.maxTries)) {
              break;
            }
          }
          if (tries > priv.maxTries) {
            throw new Error("uniqueIdService unable generate a unique ID.");
          }
          priv.history[id] = true;
          return id;
        }
      };
      return exports;
    }
  ]).factory("searchResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        employee: function(params) {
          return $http.get(DOMAIN + "/api/search/employee", {
            params: params
          });
        },
        users: function(params) {
          return $http.get(DOMAIN + "/api/search/users", {
            params: params
          });
        },
        attachments: function(params) {
          return $http.get(DOMAIN + "/api/search/users/attachments", {
            params: params
          });
        },
        usersAutocomplete: function(params) {
          return $http.get(DOMAIN + "/api/search/users/autocomplete", {
            params: params
          });
        }
      };
    }
  ]).factory("attachmentsResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/search/users/attachments", {
            params: params
          });
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/upload/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/upload/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/upload/" + id);
        }
      };
    }
  ]).factory("securityService", [
    "userResource", "$http", "$cookieStore", "$rootScope", "SESSION_COOKIE_NAME", function(userResource, $http, $cookieStore, $rootScope, SESSION_COOKIE_NAME) {
      var exports, priv;
      priv = {
        session: null,
        currentUser: null,
        requestSent: false
      };
      exports = {
        init: function(session) {
          var authorization;
          priv.requestSent = false;
          if (!session) {
            if ($cookieStore.get(SESSION_COOKIE_NAME)) {
              session = angular.fromJson($cookieStore.get(SESSION_COOKIE_NAME)) || null;
            }
          } else {
            $cookieStore.put(SESSION_COOKIE_NAME, angular.toJson(session));
          }
          priv.session = session;
          if (priv.session && priv.session.id) {
            authorization = priv.session.id;
          }
          $http.defaults.headers.common["Authorization"] = authorization;
        },
        isAuthenticated: function() {
          return !!priv.session || !!priv.currentUser;
        },
        getSession: function() {
          return priv.session;
        },
        requestCurrentUser: function() {
          if (!priv.requestSent) {
            priv.requestSent = true;
            userResource.getMe().success(function(payload) {
              priv.currentUser = payload;
              $rootScope.$broadcast("authChange");
            });
          }
          return priv.currentUser;
        },
        setUser: function(user) {
          priv.currentUser = user;
        },
        destroySession: function() {
          priv.session = null;
          priv.currentUser = null;
          priv.requestSent = false;
          $cookieStore.remove(SESSION_COOKIE_NAME);
          $http.defaults.headers.common["Authorization"] = "";
          $rootScope.$broadcast("authChange");
        }
      };
      return exports;
    }
  ]).factory("authResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        login: function(params) {
          return $http.post(DOMAIN + "/api/auth/login", params);
        },
        logout: function() {
          return $http.post(DOMAIN + "/api/auth/logout");
        }
      };
    }
  ]).factory("userResource", [
    "$http", "uniqueIdService", "DOMAIN", function($http, uniqueIdService, DOMAIN) {
      return {
        defaults: {
          group_id: 3,
          role_id: 6,
          status: "pending",
          credential: {
            email: "",
            password: uniqueIdService.generate()
          },
          meta: {
            first_name: "",
            last_name: "",
            city: ""
          },
          register: true
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/users", params);
        },
        getMe: function() {
          return $http.get(DOMAIN + "/api/users/me");
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/users/" + id, {
            params: params
          });
        },
        updateMe: function(params) {
          return $http.put(DOMAIN + "/api/users/me", params);
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/users/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/users/" + id);
        }
      };
    }
  ]).factory("refererResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        defaults: {
          name: "",
          description: "",
          status: "pending"
        },
        search: function(params) {
          return $http.get(DOMAIN + "/api/referer/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/referer", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/referer/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/referer/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/referer/" + id);
        }
      };
    }
  ]).factory("bondResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        bondtype: function(params) {
          return $http.get(DOMAIN + "/api/bond/bondtype", {
            params: params
          });
        },
        search: function(params) {
          return $http.get(DOMAIN + "/api/bond/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/bond", params);
        },
        add: function(params) {
          return $http.post(DOMAIN + "/api/bond/add", params);
        },
        remove: function(id) {
          return $http["delete"](DOMAIN + "/api/bond/remove/" + id);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/bond/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/bond/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/bond/" + id);
        }
      };
    }
  ]).factory("timelineResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/timeline/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/timeline", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/timeline/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/timeline/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/timeline/" + id);
        }
      };
    }
  ]).factory("treatmentResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/treatment/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/treatment", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/treatment/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/treatment/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/treatment/" + id);
        }
      };
    }
  ]).factory("prescriptionResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/prescription/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/prescription", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/prescription/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/prescription/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/prescription/" + id);
        }
      };
    }
  ]).factory("pathologyResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/pathologie/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/pathologie", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/pathologie/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/pathologie/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/pathologie/" + id);
        }
      };
    }
  ]).factory("reviewResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        defaults: {
          description: ""
        },
        search: function(params) {
          return $http.get(DOMAIN + "/api/review/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/review", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/review/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/review/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/review/" + id);
        }
      };
    }
  ]).factory("diagnosticResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        defaults: {
          name: "",
          description: ""
        },
        search: function(params) {
          return $http.get(DOMAIN + "/api/diagnostic/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/diagnostic", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/diagnostic/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/diagnostic/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/diagnostic/" + id);
        }
      };
    }
  ]).factory("roomResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        defaults: {
          name: "",
          description: ""
        },
        search: function(params) {
          return $http.get(DOMAIN + "/api/room/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/room", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/room/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/room/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/room/" + id);
        }
      };
    }
  ]).factory("newsResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        defaults: {
          title: "",
          description: ""
        },
        search: function(params) {
          return $http.get(DOMAIN + "/api/news/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/news", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/news/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/news/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/news/" + id);
        }
      };
    }
  ]).factory("providerResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/provider/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/provider", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/provider/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/provider/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/provider/" + id);
        }
      };
    }
  ]).factory("invoiceResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/invoice/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/invoice", params);
        },
        sendInvoice: function(params) {
          return $http.post(DOMAIN + "/api/invoice/send", params);
        },
        sendAmendments: function(params) {
          return $http.post(DOMAIN + "/api/invoice/amendments", params);
        },
        getInvoice: function(params) {
          return $http.get(DOMAIN + "/api/invoice/sent", {
            params: params
          });
        },
        getProvider: function(params) {
          return $http.get(DOMAIN + "/api/invoice/provider", {
            params: params
          });
        },
        getAmendments: function(params) {
          return $http.get(DOMAIN + "/api/invoice/amendments", {
            params: params
          });
        },
        getReceive: function(params) {
          return $http.get(DOMAIN + "/api/invoice/receive", {
            params: params
          });
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/invoice/" + id, {
            params: params
          });
        },
        removeAmendments: function(id, params) {
          return $http.put(DOMAIN + "/api/invoice/amendments/" + id, params);
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/invoice/" + id, params);
        },
        deleteInvoice: function(id) {
          return $http["delete"](DOMAIN + "/api/invoice/sent/" + id);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/invoice/" + id);
        }
      };
    }
  ]).factory("sessionResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/session/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/session", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/session/" + id, {
            params: params
          });
        },
        status: function(params) {
          return $http.post(DOMAIN + "/api/session/status", params);
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/session/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/session/" + id);
        }
      };
    }
  ]).factory("groupResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/group/search", {
            params: params
          });
        },
        all: function(params) {
          return $http.get(DOMAIN + "/api/group/all", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/group", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/group/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/group/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/group/" + id);
        }
      };
    }
  ]).factory("roleResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/role/search", {
            params: params
          });
        },
        all: function(params) {
          return $http.get(DOMAIN + "/api/role/all", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/role", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/role/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/role/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/role/" + id);
        }
      };
    }
  ]).factory("movementsResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/movements/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/movements", params);
        },
        spending: function(params) {
          return $http.post(DOMAIN + "/api/movements/spending", params);
        },
        devolution: function(params) {
          return $http.post(DOMAIN + "/api/movements/devolution", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/movements/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/movements/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/movements/" + id);
        }
      };
    }
  ]).factory("messageResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        search: function(params) {
          return $http.get(DOMAIN + "/api/message/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/message", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/message/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/message/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/message/" + id);
        }
      };
    }
  ]).factory("tagsResource", [
    "$http", "DOMAIN", function($http, DOMAIN) {
      return {
        defaults: {
          title: "",
          description: ""
        },
        search: function(params) {
          return $http.get(DOMAIN + "/api/tags/search", {
            params: params
          });
        },
        create: function(params) {
          return $http.post(DOMAIN + "/api/tags", params);
        },
        get: function(id, params) {
          return $http.get(DOMAIN + "/api/tags/" + id, {
            params: params
          });
        },
        update: function(id, params) {
          return $http.put(DOMAIN + "/api/tags/" + id, params);
        },
        "delete": function(id) {
          return $http["delete"](DOMAIN + "/api/tags/" + id);
        }
      };
    }
  ]);

}).call(this);

//# sourceMappingURL=services.js.map

;
(function() {
  angular.module("App.directives", []).directive("appVersion", [
    "version", function(version) {
      return function(scope, elm, attrs) {
        return elm.text(version);
      };
    }
  ]).directive("fixnumeric", function() {
    return {
      restrict: "A",
      require: "ngModel",
      scope: {
        model: "=ngModel"
      },
      link: function(scope, element, attrs, ngModelCtrl) {
        if (scope.model) {
          scope.model = parseInt(scope.model);
        }
      }
    };
  }).directive("maskinput", function() {
    return {
      restrict: "A",
      link: function(scope, element, attr) {
        element.inputmask();
      }
    };
  }).directive("logOut", [
    "$location", "securityService", "DEFAULT_ROUTE", function($location, securityService, DEFAULT_ROUTE) {
      return function(scope, element, attrs) {
        element.bind("click", function() {
          securityService.destroySession();
          scope.$apply(function() {
            $location.path(DEFAULT_ROUTE);
          });
        });
      };
    }
  ]).directive("goClick", [
    "$location", function($location) {
      return function(scope, element, attrs) {
        var path;
        path = void 0;
        attrs.$observe("goClick", function(val) {
          path = val;
        });
        return element.bind("click", function() {
          scope.$apply(function() {
            $location.path(path);
          });
        });
      };
    }
  ]).directive('taskFocus', [
    '$timeout', function($timeout) {
      return {
        link: function(scope, ele, attrs) {
          return scope.$watch(attrs.taskFocus, function(newVal) {
            if (newVal) {
              return $timeout(function() {
                return ele[0].focus();
              }, 0, false);
            }
          });
        }
      };
    }
  ]).directive('donutchart', [
    function() {
      return {
        restrict: 'E',
        link: function(scope, ele, attrs) {
          var chart, data, options;
          chart = null;
          options = {
            series: {
              pie: {
                show: true,
                innerRadius: 0.5
              }
            },
            legend: {
              show: true
            },
            grid: {
              hoverable: true,
              clickable: true
            },
            colors: ["#60CD9B", "#66B5D7", "#EEC95A", "#E87352"],
            tooltip: true,
            tooltipOpts: {
              content: "%p.0%, %s",
              defaultTheme: false
            }
          };
          data = scope[attrs.ngModel];
          return scope.$watch("donutChart", function(v) {
            if (!chart) {
              chart = $.plot(ele[0], v, options);
              return ele.show();
            } else {
              chart.setData(v);
              chart.setupGrid();
              return chart.draw();
            }
          });
        }
      };
    }
  ]).directive('linechart', [
    function() {
      return {
        restrict: 'E',
        link: function(scope, ele, attrs) {
          var chart, data, options;
          chart = null;
          options = {
            series: {
              pie: {
                show: true
              }
            },
            legend: {
              show: true
            },
            grid: {
              hoverable: true,
              clickable: true
            },
            colors: ["#60CD9B", "#66B5D7", "#EEC95A", "#E87352"],
            tooltip: true,
            tooltipOpts: {
              content: "%p.0%, %s",
              defaultTheme: false
            }
          };
          data = scope[attrs.ngModel];
          return scope.$watch("lineChart", function(v) {
            if (!chart) {
              chart = $.plot(ele[0], v, options);
              return ele.show();
            } else {
              chart.setData(v);
              chart.setupGrid();
              return chart.draw();
            }
          });
        }
      };
    }
  ]).directive("imgOnLoad", function() {
    return {
      restrict: "C",
      link: function(scope, element, attrs) {
        return element.bind("load", function(e) {
          element.addClass("loaded");
        });
      }
    };
  }).directive('imgHolder', [
    function() {
      return {
        restrict: 'A',
        link: function(scope, ele, attrs) {
          return Holder.run({
            images: ele[0]
          });
        }
      };
    }
  ]).directive('customBackground', function() {
    return {
      restrict: "A",
      controller: [
        '$scope', '$element', '$location', function($scope, $element, $location) {
          var addBg, path;
          path = function() {
            return $location.path();
          };
          addBg = function(path) {
            $element.removeClass('body-home body-special body-tasks body-lock');
            switch (path) {
              case '/':
                return $element.addClass('body-home');
              case '/404':
              case '/500':
              case '/user/lock':
              case '/user/signin':
              case '/user/signup':
              case '/user/forgot':
                return $element.addClass('body-special');
              case '/user/lock-screen':
                return $element.addClass('body-special body-lock');
              case '/tasks':
                return $element.addClass('body-tasks');
            }
          };
          addBg($location.path());
          return $scope.$watch(path, function(newVal, oldVal) {
            if (newVal === oldVal) {
              return;
            }
            return addBg($location.path());
          });
        }
      ]
    };
  }).directive('toggleMinNav', [
    '$rootScope', function($rootScope) {
      return {
        restrict: 'A',
        link: function(scope, ele, attrs) {
          var $content, $nav, $window, app, updateClass;
          app = $('#app');
          $window = $(window);
          $nav = $('#nav-container');
          $content = $('#content');
          ele.on('click', function(e) {
            if (app.hasClass('nav-min')) {
              app.removeClass('nav-min');
            } else {
              app.addClass('nav-min');
              $rootScope.$broadcast('minNav:enabled');
            }
            return e.preventDefault();
          });
          updateClass = function() {
            var width;
            width = $window.width();
            if (width < 768) {
              return app.removeClass('nav-min');
            }
          };
          return $window.resize(function() {
            var t;
            clearTimeout(t);
            return t = setTimeout(updateClass, 300);
          });
        }
      };
    }
  ]).directive('collapseNav', [
    function() {
      return {
        restrict: 'A',
        link: function(scope, ele, attrs) {
          var $a, $aRest, $lists, $listsRest, app;
          $lists = ele.find('ul').parent('li');
          $lists.append('<i class="fa fa-caret-right icon-has-ul"></i>');
          $a = $lists.children('a');
          $listsRest = ele.children('li').not($lists);
          $aRest = $listsRest.children('a');
          app = $('#app');
          $a.on('click', function(event) {
            var $parent, $this;
            if (app.hasClass('nav-min')) {
              return false;
            }
            $this = $(this);
            $parent = $this.parent('li');
            $lists.not($parent).removeClass('open').find('ul').slideUp();
            $parent.toggleClass('open').find('ul').slideToggle();
            return event.preventDefault();
          });
          $aRest.on('click', function(event) {
            return $lists.removeClass('open').find('ul').slideUp();
          });
          return scope.$on('minNav:enabled', function(event) {
            return $lists.removeClass('open').find('ul').slideUp();
          });
        }
      };
    }
  ]).directive('highlightActive', [
    function() {
      return {
        restrict: "A",
        controller: [
          '$scope', '$element', '$attrs', '$location', function($scope, $element, $attrs, $location) {
            var highlightActive, links, path;
            links = $element.find('a');
            path = function() {
              return $location.path();
            };
            highlightActive = function(links, path) {
              path = '#' + path;
              return angular.forEach(links, function(link) {
                var $li, $link, href;
                $link = angular.element(link);
                $li = $link.parent('li');
                href = $link.attr('href');
                if ($li.hasClass('active')) {
                  $li.removeClass('active');
                }
                if (path.indexOf(href) === 0) {
                  return $li.addClass('active');
                }
              });
            };
            highlightActive(links, $location.path());
            return $scope.$watch(path, function(newVal, oldVal) {
              if (newVal === oldVal) {
                return;
              }
              return highlightActive(links, $location.path());
            });
          }
        ]
      };
    }
  ]).directive('toggleOffCanvas', [
    function() {
      return {
        restrict: 'A',
        link: function(scope, ele, attrs) {
          return ele.on('click', function() {
            return $('#app').toggleClass('on-canvas');
          });
        }
      };
    }
  ]).directive('slimScroll', [
    function() {
      return {
        restrict: 'A',
        link: function(scope, ele, attrs) {
          return ele.slimScroll({
            height: attrs.scrollHeight || '100%'
          });
        }
      };
    }
  ]).directive('goBack', [
    function() {
      return {
        restrict: "A",
        controller: [
          '$scope', '$element', '$window', function($scope, $element, $window) {
            return $element.on('click', function() {
              return $window.history.back();
            });
          }
        ]
      };
    }
  ]).directive("myCurrentTime", [
    "$interval", "dateFilter", function($interval, dateFilter) {
      return function(scope, element, attrs) {
        var format, stopTime, updateTime;
        updateTime = function() {
          element.text(dateFilter(new Date(), format));
        };
        format = void 0;
        stopTime = void 0;
        scope.$watch(attrs.myCurrentTime, function(value) {
          format = value;
          updateTime();
        });
        stopTime = $interval(updateTime, 1000);
        element.on("$destroy", function() {
          $interval.cancel(stopTime);
        });
      };
    }
  ]).directive('sessionList', [
    function() {
      return {
        restrict: 'AE',
        replace: true,
        scope: {
          prescription: "=",
          user: "=",
          rooms: "="
        },
        controller: [
          "$rootScope", "$scope", "$modal", "sessionResource", function($rootScope, $scope, $modal, sessionResource) {
            $scope.statuses = [];
            $scope.toggleStatus = function(id) {
              var idx;
              idx = $scope.statuses.indexOf(id);
              if (idx > -1) {
                $scope.statuses.splice(idx, 1);
              } else {
                $scope.statuses.push(id);
              }
            };
            $scope.missed = function() {
              return sessionResource.status({
                checked: $scope.statuses,
                status: '-1'
              }).success(function(payload) {
                $scope.statuses = [];
                $rootScope.$broadcast("success", "Session has been updated missed successfully!");
                sessionResource.search({
                  prescription_id: $scope.prescription.id
                }).success(function(payload) {
                  return $scope.sessions = payload.data;
                });
              });
            };
            $scope.received = function() {
              sessionResource.status({
                checked: $scope.statuses,
                status: '1'
              }).success(function(payload) {
                $scope.statuses = [];
                $rootScope.$broadcast("success", "Session has been updated received successfully!");
                return sessionResource.search({
                  prescription_id: $scope.prescription.id
                }).success(function(payload) {
                  return $scope.sessions = payload.data;
                });
              });
            };
            $scope.schedule = function() {
              var $parentScope, modal, prescriptionSessionDialogController;
              $parentScope = $scope;
              modal = $modal.open({
                backdrop: true,
                keyboard: true,
                templateUrl: "html/modal/session.html",
                controller: prescriptionSessionDialogController = [
                  "$scope", "$modalInstance", "sessionResource", function($scope, $modalInstance, sessionResource) {
                    $scope.rooms = $parentScope.rooms;
                    $scope.session = {
                      id: "",
                      patient: $parentScope.user
                    };
                    $scope.mindate = new Date();
                    $scope.dateOptions = {
                      startingDay: 1,
                      showWeeks: "false"
                    };
                    $scope.open = function($event) {
                      $event.preventDefault();
                      $event.stopPropagation();
                      $scope.opened = true;
                    };
                    $scope.submit = function() {
                      if ($scope.session.id) {
                        sessionResource.update($scope.session.id, $scope.session).success(function(payload) {
                          $rootScope.$broadcast("success", "Session has been updated successfully!");
                          $modalInstance.close(payload);
                        });
                      } else {
                        sessionResource.create(_.extend($scope.session, {
                          "prescription": $parentScope.prescription,
                          "diagnostic": {
                            id: $parentScope.prescription.diagnostic_id
                          }
                        })).success(function(payload) {
                          $rootScope.$broadcast("success", "Session has been created successfully!");
                          $modalInstance.close(payload);
                        });
                      }
                    };
                    $scope.dismiss = function() {
                      $modalInstance.close();
                    };
                  }
                ]
              });
              return modal.result.then(function(session) {
                if (session) {
                  sessionResource.search({
                    prescription_id: $scope.prescription.id
                  }).success(function(payload) {
                    return $scope.sessions = payload.data;
                  });
                }
              });
            };
            $scope.open = function(id) {
              var modal, noteDialogController;
              return modal = $modal.open({
                backdrop: true,
                keyboard: true,
                templateUrl: "html/modal/session-note.html",
                controller: noteDialogController = [
                  "$rootScope", "$scope", "$modalInstance", "sessionResource", function($rootScope, $scope, $modalInstance, sessionResource) {
                    if (!parseInt(id)) {
                      $scope.session = {
                        note: ""
                      };
                    } else {
                      sessionResource.get(id).success(function(payload) {
                        $scope.session = payload;
                      });
                    }
                    $scope.submit = function() {
                      if ($scope.session.id) {
                        sessionResource.update($scope.session.id, $scope.session).success(function(payload) {
                          $rootScope.$broadcast("success", "Note has been updated successfully!");
                          $modalInstance.close();
                        });
                      } else {
                        $rootScope.$broadcast("error", "Note can update!");
                        $modalInstance.close();
                        return;
                      }
                    };
                    $scope.dismiss = function() {
                      $modalInstance.close();
                    };
                  }
                ]
              });
            };
            return sessionResource.search({
              prescription_id: $scope.prescription.id
            }).success(function(payload) {
              $scope.sessions = payload.data;
            });
          }
        ],
        templateUrl: 'html/directive/session.html'
      };
    }
  ]).directive('uiSpinner', function() {
    return {
      link: function(scope, ele) {
        ele.addClass('ui-spinner');
        return ele.spinner();
      }
    };
  });

}).call(this);

//# sourceMappingURL=directives.js.map

;
(function() {
  angular.module("App.filters", []).filter("unsafe", [
    "$sce", function($sce) {
      return function(val) {
        return $sce.trustAsHtml(val);
      };
    }
  ]).filter("getAvatar", function() {
    return function(a) {
      if (!a) {
        a = "img/avatar.jpg";
      }
      return a;
    };
  }).filter("percent", function() {
    return function(val) {
      if (isNaN(val)) {
        return "0%";
      }
      if (parseFloat(val) == null) {
        return "0%";
      } else {
        return String(val * 100) + "%";
      }
    };
  }).filter("sumby", function($filter, CURRENCY) {
    return function(data, key) {
      var sum;
      if (typeof data === "undefined") {
        return 0;
      }
      sum = 0;
      angular.forEach(data, function(value) {
        if (value.payment_type === key || typeof key === "undefined") {
          return sum += parseFloat(value.amount);
        }
      });
      return $filter("currency")(sum, CURRENCY, 2);
    };
  }).filter("totalvat", function() {
    return function(val, vat) {
      if (vat == null) {
        vat = 0;
      }
      if (isNaN(val) && !parseFloat(val)) {
        return 0;
      }
      return parseFloat(val) + (parseFloat(val) * parseFloat(vat));
    };
  }).filter("devolution", function() {
    return function(val) {
      val = (val == null ? val : parseInt(val));
      return -1 * val;
    };
  }).filter("age", function() {
    return function(arg) {
      var today;
      if (parseInt(arg)) {
        today = new Date();
        return parseInt(today.getFullYear() - parseInt(arg));
      }
      return arg;
    };
  }).filter("fixbr", function() {
    return function(arg) {
      if (arg) {
        return arg.replace(/&lt;br(.*?)\/&gt;/g, "<br />");
      }
    };
  }).filter("characters", function() {
    return function(input, chars, breakOnWord) {
      var lastspace;
      if (isNaN(chars)) {
        return input;
      }
      if (chars <= 0) {
        return "";
      }
      if (input && input.length >= chars) {
        input = input.substring(0, chars);
        if (!breakOnWord) {
          lastspace = input.lastIndexOf(" ");
          if (lastspace !== -1) {
            input = input.substr(0, lastspace);
          }
        } else {
          while (input.charAt(input.length - 1) === " ") {
            input = input.substr(0, input.length - 1);
          }
        }
        return input + "...";
      }
      return input;
    };
  }).filter("plaintext", function() {
    return function(str) {
      if (str == null) {
        return "";
      } else {
        return String(str).replace(/<[^>]+>/gm, ' ');
      }
    };
  }).filter("capitalize", function() {
    return function(str) {
      str = (str == null ? "" : String(str));
      return str.charAt(0).toUpperCase() + str.slice(1);
    };
  }).filter("invoice", function() {
    return function(str) {
      return str = (str == null ? "" : "FA-" + String(str));
    };
  }).filter("titleize", function() {
    return function(str) {
      if (str == null) {
        return "";
      }
      str = String(str).toLowerCase();
      return str.replace(/(?:^|\s|-)\S/g, function(c) {
        return c.toUpperCase();
      });
    };
  }).filter("words", function() {
    return function(input, words) {
      var inputWords;
      if (isNaN(words)) {
        return input;
      }
      if (words <= 0) {
        return "";
      }
      if (input) {
        inputWords = input.split(/\s+/);
        if (inputWords.length > words) {
          input = inputWords.slice(0, words).join(" ") + "...";
        }
      }
      return input;
    };
  }).filter("mydate", function($filter) {
    return function(str) {
      var tempdate;
      tempdate = new Date(str.replace(/-/g, "/"));
      return $filter("date")(tempdate, "dd/MM/yyyy");
    };
  }).filter("mydatetime", function($filter) {
    return function(str) {
      var tempdate;
      tempdate = new Date(str.replace(/-/g, "/"));
      return $filter("date")(tempdate, "dd/MM/yyyy HH:mm:ss");
    };
  }).filter("timeago", function() {
    return function(time, local, raw) {
      var DAY, DECADE, HOUR, MINUTE, MONTH, WEEK, YEAR, offset, span;
      if (!time) {
        return "never";
      }
      if (!local) {
        local = Date.now();
      }
      if (angular.isDate(time)) {
        time = time.getTime();
      } else {
        if (typeof time === "string") {
          time = new Date(time).getTime();
        }
      }
      if (angular.isDate(local)) {
        local = local.getTime();
      } else {
        if (typeof local === "string") {
          local = new Date(local).getTime();
        }
      }
      if (typeof time !== "number" || typeof local !== "number") {
        return;
      }
      offset = Math.abs((local - time) / 1000);
      span = [];
      MINUTE = 60;
      HOUR = 3600;
      DAY = 86400;
      WEEK = 604800;
      MONTH = 2629744;
      YEAR = 31556926;
      DECADE = 315569260;
      if (offset <= MINUTE) {
        span = ["", (raw ? "now" : "less than a minute")];
      } else if (offset < (MINUTE * 60)) {
        span = [Math.round(Math.abs(offset / MINUTE)), "min"];
      } else if (offset < (HOUR * 24)) {
        span = [Math.round(Math.abs(offset / HOUR)), "hr"];
      } else if (offset < (DAY * 7)) {
        span = [Math.round(Math.abs(offset / DAY)), "day"];
      } else if (offset < (WEEK * 52)) {
        span = [Math.round(Math.abs(offset / WEEK)), "week"];
      } else if (offset < (YEAR * 10)) {
        span = [Math.round(Math.abs(offset / YEAR)), "year"];
      } else if (offset < (DECADE * 100)) {
        span = [Math.round(Math.abs(offset / DECADE)), "decade"];
      } else {
        span = ["", "a long time"];
      }
      span[1] += (span[0] === 0 || span[0] > 1 ? "s" : "");
      span = span.join(" ");
      if (raw === true) {
        return span;
      }
      if (time <= local) {
        return span + " ago";
      } else {
        return "in " + span;
      }
    };
  });

}).call(this);

//# sourceMappingURL=filters.js.map

;
(function() {
  angular.module("App.controllers", []).controller("ProviderCtrl", [
    "$rootScope", "$scope", "$routeParams", "$location", "$fileUploader", "providerResource", "PostalData", "CountryData", function($rootScope, $scope, $routeParams, $location, $fileUploader, providerResource, PostalData, CountryData) {
      var id;
      id = $routeParams.id;
      if (!parseInt(id)) {
        $scope.provider = {
          id: ""
        };
        $scope.reset = angular.copy($scope.provider);
      } else {
        providerResource.get(id, {}).success(function(payload) {
          $scope.provider = payload;
          $scope.reset = angular.copy($scope.provider);
        });
      }
      $scope.onZipcode = function(zipcode) {
        var key;
        key = zipcode.substring(0, 2);
        return angular.forEach(PostalData, function(postal) {
          if (postal.key === key) {
            $scope.provider.city = postal.name;
          }
        });
      };
      $scope.dismiss = function() {
        angular.copy($scope.reset, $scope.provider);
      };
      $scope.countries = CountryData;
      $scope.postals = PostalData;
      $scope.submit = function() {
        if ($scope.provider.id) {
          return providerResource.update($scope.provider.id, $scope.provider).success(function(payload) {
            $rootScope.$broadcast("success", "Provider has been updated successfully!");
            $scope.provider = payload;
          });
        } else {
          return providerResource.create($scope.provider).success(function(payload) {
            $rootScope.$broadcast("success", "Provider has been created successfully!");
          });
        }
      };
    }
  ]).controller("ProvidersCtrl", [
    "$rootScope", "$scope", "$modal", "$location", "providerResource", "PERPAGE", function($rootScope, $scope, $modal, $location, providerResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      providerResource.search($location.search()).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.open = function(id) {
        var $parentScope, modal, providerDialogController;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/provider.html",
          controller: providerDialogController = [
            "$scope", "$modalInstance", "CountryData", "PostalData", "providerResource", function($scope, $modalInstance, CountryData, PostalData, providerResource) {
              $scope.countries = CountryData;
              $scope.postals = PostalData;
              if (!parseInt(id)) {
                $scope.provider = {
                  id: ""
                };
              } else {
                providerResource.get(id, {}).success(function(payload) {
                  $scope.provider = payload;
                });
              }
              $scope.submit = function() {
                if ($scope.provider.id) {
                  return providerResource.update($scope.provider.id, $scope.provider).success(function(payload) {
                    $rootScope.$broadcast("success", "Provider has been updated successfully!");
                    $scope.provider = payload;
                    $modalInstance.close($scope.provider);
                  });
                } else {
                  return providerResource.create($scope.provider).success(function(payload) {
                    $rootScope.$broadcast("success", "Provider has been created successfully!");
                    $modalInstance.close($scope.provider);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(provider) {
          if (provider) {
            providerResource.search({}).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          return providerResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Provider file has been deleted successfully!");
            return providerResource.search().success(function(payload) {
              return $scope.results = payload;
            });
          });
        }
      };
      $scope.search = function() {
        return $location.search({
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        return $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        return $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.changeLimit = function(limit) {
        return $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
    }
  ]).controller("InvoiceCtrl", [
    "$rootScope", "$scope", "$routeParams", "invoiceResource", function($rootScope, $scope, $routeParams, invoiceResource) {
      var id;
      id = $routeParams.id;
      invoiceResource.get(id, {}).success(function(payload) {
        $scope.invoice = payload;
      });
    }
  ]).controller("ProviderInvoicesCtrl", [
    "$rootScope", "$scope", "$http", "$modal", "$timeout", "$location", "$fileUploader", "$routeParams", "invoiceResource", "providerResource", "CountryData", "PostalData", "DOMAIN", "PERPAGE", function($rootScope, $scope, $http, $modal, $timeout, $location, $fileUploader, $routeParams, invoiceResource, providerResource, CountryData, PostalData, DOMAIN, PERPAGE) {
      var id, uploader;
      $scope.countries = CountryData;
      $scope.postals = PostalData;
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.token = $http.defaults.headers.common["Authorization"];
      $scope.dateOptions = {
        startingDay: 1,
        showWeeks: "false"
      };
      id = $routeParams.id;
      $scope.invoice = {};
      if (!parseInt(id)) {
        $scope.provider = {
          id: ""
        };
        $scope.reset = angular.copy($scope.provider);
        $scope.results = [];
      } else {
        providerResource.get(id, {}).success(function(payload) {
          $scope.provider = payload;
          $scope.reset = angular.copy($scope.provider);
          invoiceResource.search({
            provider_id: id
          }).success(function(payload) {
            return $scope.results = payload;
          });
        });
      }
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.onZipcode = function(zipcode) {
        var key;
        key = zipcode.substring(0, 2);
        return angular.forEach(PostalData, function(postal) {
          if (postal.key === key) {
            $scope.provider.city = postal.name;
          }
        });
      };
      $scope.dismiss = function() {
        angular.copy($scope.reset, $scope.provider);
      };
      $scope.submit = function() {
        if ($scope.provider.id) {
          return providerResource.update($scope.provider.id, $scope.provider).success(function(payload) {
            $rootScope.$broadcast("success", "Provider has been updated successfully!");
            $scope.provider = payload;
          });
        } else {
          return providerResource.create($scope.provider).success(function(payload) {
            $rootScope.$broadcast("success", "Provider has been created successfully!");
          });
        }
      };
      $scope.create = function() {
        console.log($scope.attachment);
        invoiceResource.create(_.extend($scope.invoice, {
          attachment: $scope.attachment,
          provider_id: id
        })).success(function(payload) {
          $scope.invoice = {};
          $rootScope.$broadcast("success", "Provider Invoice has been created successfully!");
          return invoiceResource.search({
            provider_id: id
          }).success(function(payload) {
            $scope.results = payload;
          });
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          return invoiceResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Provider Invoice file has been deleted successfully!");
            return invoiceResource.search().success(function(payload) {
              return $scope.results = payload;
            });
          });
        }
      };
      uploader = $scope.uploader = $fileUploader.create({
        scope: $scope,
        url: DOMAIN + "/api/upload" + "?pid=" + id,
        headers: {
          Authorization: $scope.token
        },
        autoUpload: true,
        removeAfterUpload: true,
        formData: [],
        filters: []
      });
      uploader.bind("progressall", function(event, progress) {
        $scope.progress = progress;
        $scope.showprogress = true;
      });
      uploader.bind("complete", function(event, xhr, item, response) {
        $scope.attachment = response.payload.id;
        $timeout((function() {
          $scope.showprogress = false;
        }), 3000);
        return console.log($scope.attachment);
      });
      $scope.viewInvoice = function(invoice) {
        var modal, viewInvoiceDialogController;
        return modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/invoice.html",
          controller: viewInvoiceDialogController = [
            "$scope", "$modalInstance", function($scope, $modalInstance) {
              $scope.invoice = invoice;
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
      };
      $scope.open = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
      };
    }
  ]).controller("ProvidersInvoicesCtrl", [
    "$rootScope", "$scope", "$modal", "$location", "invoiceResource", "PERPAGE", function($rootScope, $scope, $modal, $location, invoiceResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      invoiceResource.search($location.search()).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.open = function(id) {
        var $parentScope, modal, providerInvoicesDialogController;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/invoice.html",
          controller: providerInvoicesDialogController = [
            "$scope", "$modalInstance", "CountryData", "PostalData", "invoiceResource", function($scope, $modalInstance, CountryData, PostalData, invoiceResource) {
              $scope.countries = CountryData;
              $scope.postals = PostalData;
              if (!parseInt(id)) {
                $scope.provider = {
                  id: ""
                };
              } else {
                invoiceResource.get(id, {}).success(function(payload) {
                  $scope.provider = payload;
                });
              }
              $scope.submit = function() {
                if ($scope.provider.id) {
                  return invoiceResource.update($scope.provider.id, $scope.provider).success(function(payload) {
                    $rootScope.$broadcast("success", "Provider has been updated successfully!");
                    $scope.provider = payload;
                    $modalInstance.close($scope.provider);
                  });
                } else {
                  return invoiceResource.create($scope.provider).success(function(payload) {
                    $rootScope.$broadcast("success", "Provider has been created successfully!");
                    $modalInstance.close($scope.provider);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(provider) {
          if (provider) {
            invoiceResource.search({}).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          return invoiceResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Provider file has been deleted successfully!");
            return invoiceResource.search().success(function(payload) {
              return $scope.results = payload;
            });
          });
        }
      };
      $scope.viewInvoice = function(invoice) {
        var modal, viewInvoiceDialogController;
        return modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/invoice.html",
          controller: viewInvoiceDialogController = [
            "$scope", "$modalInstance", function($scope, $modalInstance) {
              $scope.invoice = invoice;
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
      };
    }
  ]).controller("InvoicesCtrl", [
    "$rootScope", "$scope", "$modal", "$location", "invoiceResource", function($rootScope, $scope, $modal, $location, invoiceResource) {
      invoiceResource.search({}).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.open = function(id) {
        var $parentScope, modal, providerDialogController;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/provider.html",
          controller: providerDialogController = [
            "$scope", "$modalInstance", "CountryData", "PostalData", "invoiceResource", function($scope, $modalInstance, CountryData, PostalData, invoiceResource) {
              $scope.countries = CountryData;
              $scope.postals = PostalData;
              if (!parseInt(id)) {
                $scope.provider = {
                  id: ""
                };
              } else {
                invoiceResource.get(id, {}).success(function(payload) {
                  $scope.provider = payload;
                });
              }
              $scope.submit = function() {
                if ($scope.provider.id) {
                  return invoiceResource.update($scope.provider.id, $scope.provider).success(function(payload) {
                    $rootScope.$broadcast("success", "Provider has been updated successfully!");
                    $scope.provider = payload;
                    $modalInstance.close($scope.provider);
                  });
                } else {
                  return invoiceResource.create($scope.provider).success(function(payload) {
                    $rootScope.$broadcast("success", "Provider has been created successfully!");
                    $modalInstance.close($scope.provider);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(provider) {
          if (provider) {
            invoiceResource.search({}).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          return invoiceResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Provider file has been deleted successfully!");
            return invoiceResource.search().success(function(payload) {
              return $scope.results = payload;
            });
          });
        }
      };
    }
  ]).controller('AppCtrl', [
    '$scope', '$location', function($scope, $location) {
      $scope.isHide = function() {
        var path;
        path = $location.path();
        return _.contains(['/404', '/500', '/user/lock', '/user/signin', '/user/signup', '/user/forgot'], path);
      };
      return $scope.main = {
        brand: 'Clinic Management',
        name: 'Admin'
      };
    }
  ]).controller("HeaderCtrl", [
    "$scope", "$rootScope", "$routeParams", "$location", "$interval", "securityService", "messageResource", "userResource", "searchResource", function($scope, $rootScope, $routeParams, $location, $interval, securityService, messageResource, userResource, searchResource) {
      var id;
      id = $routeParams.id;
      if (id === "new") {
        $scope.user = userResource.defaults;
      } else {
        if (!parseInt(id)) {
          id = "me";
        }
        userResource.get(id, {}).success(function(payload) {
          $scope.user = payload;
        });
      }
      $scope.isAuthenticated = securityService.isAuthenticated();
      $scope.syncMsg = function() {
        if ($scope.isAuthenticated) {
          return messageResource.search({}).success(function(payload) {
            return $scope.messages = payload;
          });
        }
      };
      $interval($scope.syncMsg(), 60000);
      $scope.syncMsg;
      $scope.$on("authChange", function(event) {
        $scope.isAuthenticated = securityService.isAuthenticated();
        if ($scope.isAuthenticated) {
          $scope.user = securityService.requestCurrentUser();
        } else {
          $scope.user = null;
        }
      });
    }
  ]).controller('NavCtrl', [
    '$scope', 'securityService', function($scope, securityService) {
      $scope.isAdmin = function() {
        if ($scope.isAuthenticated) {
          if ($scope.user.role_id[0].name === "admin") {
            return true;
          }
        }
        return false;
      };
      return $scope.$on("authChange", function(event) {
        $scope.isAuthenticated = securityService.isAuthenticated();
        if ($scope.isAuthenticated) {
          $scope.user = securityService.requestCurrentUser();
        } else {
          $scope.user = null;
        }
      });
    }
  ]).controller("SignoutCtrl", ["$scope", function($scope) {}]).controller("LockCtrl", [
    "$scope", "$rootScope", "$location", "securityService", "authResource", "DEFAULT_ROUTE", function($scope, $rootScope, $location, securityService, authResource, DEFAULT_ROUTE) {
      var ref;
      $scope.user = securityService.requestCurrentUser();
      securityService.destroySession();
      ref = $location.$$url;
      $scope.password = "";
      if (!$scope.user.credential.email) {
        $location.path("user/signin");
      }
      $scope.submit = function() {
        authResource.login({
          username: $scope.user.credential.email,
          password: $scope.password
        }).success(function(payload) {
          securityService.init(payload);
          $rootScope.$broadcast("success", "Welcome!");
          return $location.path(DEFAULT_ROUTE);
        });
      };
    }
  ]).controller("SigninCtrl", [
    "$scope", "$rootScope", "$location", "authResource", "securityService", "DEFAULT_ROUTE", "REQUIRE_AUTH", function($scope, $rootScope, $location, authResource, securityService, DEFAULT_ROUTE, REQUIRE_AUTH) {
      $scope.username = "admin@example.com";
      $scope.password = "password";
      $scope.submit = function() {
        $scope.form.$setDirty();
        if ($scope.form.$valid) {
          authResource.login({
            username: $scope.username,
            password: $scope.password
          }).success(function(payload) {
            var path;
            securityService.init(payload);
            $rootScope.$broadcast("success", "Welcome!");
            if ($rootScope.isPath && $rootScope.isPath !== REQUIRE_AUTH) {
              path = $rootScope.isPath;
            } else {
              path = DEFAULT_ROUTE;
            }
            return $location.path(path);
          });
        }
      };
    }
  ]).controller("SignupCtrl", ["$scope", "$rootScope", "$location", "userResource", "authResource", "securityService", "DEFAULT_ROUTE", function($scope, $rootScope, $location, userResource, authResource, securityService, DEFAULT_ROUTE) {}]).controller("PasswordCtrl", [
    "$rootScope", "$scope", "$location", "userResource", "securityService", "REQUIRE_AUTH", function($rootScope, $scope, $location, userResource, securityService, REQUIRE_AUTH) {
      $scope.currentUser = securityService.requestCurrentUser();
      if (securityService.isAuthenticated()) {
        userResource.getMe().success(function(payload) {
          $scope.user = payload;
        });
      } else {
        $rootScope.$broadcast("danger", "Error: You must login again!");
        $location.path(REQUIRE_AUTH);
        return;
      }
      $scope.save = function() {
        $scope.form.$setDirty();
        if ($scope.form.$valid) {
          if ($scope.user.id) {
            userResource.updateMe($scope.user).success(function(payload) {
              $rootScope.$broadcast("success", "User has been changed password successfully!");
            });
          } else {
            $rootScope.$broadcast("danger", "Error: User is not found!");
            return;
          }
        } else {
          $rootScope.$broadcast("danger", "Error: Not changed password!");
          return;
        }
      };
    }
  ]).controller("CalendarsCtrl", [
    "$scope", "$modal", "$location", "$rootScope", "diagnosticResource", "searchResource", "roomResource", "treatmentResource", "sessionResource", "userResource", function($scope, $modal, $location, $rootScope, diagnosticResource, searchResource, roomResource, treatmentResource, sessionResource, userResource) {
      $scope.eventSources = $scope.events = [];
      $scope.room = $location.search().room_id || "";
      $scope.employee = $location.search().user_id || "";
      $scope.isCollapsed = true;
      searchResource.employee({}).success(function(payload) {
        return $scope.employees = payload.data;
      });
      roomResource.search({
        status: 1
      }).success(function(payload) {
        return $scope.rooms = payload.data;
      });
      diagnosticResource.search({
        status: 1
      }).success(function(payload) {
        return $scope.diagnostics = payload.data;
      });
      treatmentResource.search({
        active: 1
      }).success(function(payload) {
        return $scope.treatments = payload.data;
      });
      userResource.get(1, {}).success(function(payload) {
        return $scope.clinic = payload.clinic;
      });
      $scope.goTo = function(query) {
        $scope.loadingState = true;
        return $location.search(_.extend($location.search(), query));
      };
      $scope.overlay = $(".fc-overlay");
      $scope.alertOnEventClick = function(event, jsEvent, view) {
        console.log(event);
      };
      $scope.alertOnDrop = function(event, delta, revertFunc, jsEvent, ui, view) {
        console.log("Event Droped to make dayDelta " + delta);
      };
      $scope.alertOnResize = function(event, delta, revertFunc, jsEvent, ui, view) {
        sessionResource.get(event.id).success(function(payload) {
          $scope.session = payload;
          if ($scope.session.id) {
            $scope.session.scheduled_at = event.start;
            return sessionResource.update($scope.session.id, $scope.session).success(function(payload) {
              return $rootScope.$broadcast("success", "Session has been updated successfully!");
            });
          }
        });
      };
      $scope.alertOnMouseOver = function(event, jsEvent, view) {
        var cal, left, right, wrap;
        $scope.event = event;
        $scope.overlay.removeClass("left right").find(".arrow").removeClass("left right top pull-up");
        wrap = $(jsEvent.target).closest(".fc-event");
        cal = wrap.closest(".calendar");
        left = wrap.offset().left - cal.offset().left;
        right = cal.width() - (wrap.offset().left - cal.offset().left + wrap.width());
        if (right > $scope.overlay.width()) {
          $scope.overlay.addClass("left").find(".arrow").addClass("left pull-up");
        } else if (left > $scope.overlay.width()) {
          $scope.overlay.addClass("right").find(".arrow").addClass("right pull-up");
        } else {
          $scope.overlay.find(".arrow").addClass("top");
        }
        (wrap.find(".fc-overlay").length === 0) && wrap.append($scope.overlay);
      };
      $scope.uiConfig = {
        calendar: {
          firstDay: 1,
          height: 450,
          editable: true,
          header: {
            left: "prev",
            center: "title",
            right: "next"
          },
          disableDragging: true,
          eventClick: $scope.alertOnEventClick,
          eventDrop: $scope.alertOnDrop,
          eventResize: $scope.alertOnResize,
          eventMouseover: $scope.alertOnMouseOver
        }
      };
      $scope.changeView = function(view, calendar) {
        calendar.fullCalendar("changeView", view);
      };
      $scope.today = function(calendar) {
        calendar.fullCalendar("today");
      };
      $scope.renderCalender = function(calendar) {
        if (calendar) {
          calendar.fullCalendar("render");
        }
      };
      $scope["new"] = function() {
        var $parentScope, modal, scheduleDialogController;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/schedule.html",
          controller: scheduleDialogController = [
            "$scope", "$modalInstance", "searchResource", "sessionResource", "diagnosticResource", function($scope, $modalInstance, searchResource, sessionResource, diagnosticResource) {
              $scope.session = {
                id: ""
              };
              $scope.rooms = $parentScope.rooms;
              $scope.treatments = $parentScope.treatments;
              $scope.diagnostics = $parentScope.diagnostics;
              $scope.mindate = new Date();
              $scope.dateOptions = {
                startingDay: 1,
                showWeeks: "false"
              };
              $scope.getPatient = function(val) {
                return searchResource.users({
                  'role': "user",
                  q: val
                }).then(function(payload) {
                  var patients;
                  patients = [];
                  angular.forEach(payload.data.data, function(item) {
                    patients.push({
                      id: item.id,
                      fullname: item.first_name + ' ' + item.last_name
                    });
                  });
                  return patients;
                });
              };
              $scope.open = function($event) {
                $event.preventDefault();
                $event.stopPropagation();
                $scope.opened = true;
              };
              $scope.loadEmployees = function(treatment) {
                $scope.loadingEmployees = true;
                $scope.employees = [];
                return searchResource.users({
                  'role': treatment.role
                }).success(function(payload) {
                  $scope.employees = payload.data;
                  $scope.loadingEmployees = false;
                });
              };
              $scope.$watch("session.patient", function(patient) {
                if (patient && patient.id) {
                  return $scope.loadDiagnostics(patient.id);
                }
              });
              $scope.loadDiagnostics = function(patient_id) {
                return diagnosticResource.search({
                  uid: patient_id
                }).success(function(payload) {
                  return $scope.diagnostics = payload.data || [];
                });
              };
              $scope.submit = function() {
                if ($scope.session.id) {
                  sessionResource.update($scope.session.id, $scope.session).success(function(payload) {
                    $rootScope.$broadcast("success", "Schedule has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                } else {
                  sessionResource.create(_.extend($scope.session, {})).success(function(payload) {
                    $rootScope.$broadcast("success", "Schedule has been created successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(session) {
          if (session) {
            $location.search(_.extend($location.search(), {
              refresh: (new Date()).getTime()
            }));
            return;
          }
        });
      };
      $scope.loadSchedule = function() {
        var events;
        events = [];
        sessionResource.search($location.search()).success(function(payload) {
          var randomClass, sessions;
          sessions = payload.data;
          randomClass = ["b-primary", "b-info", "b-success", "b-danger", "b-warning"];
          return angular.forEach(sessions, function(value) {
            var random;
            random = Math.floor(Math.random() * randomClass.length);
            return events.push({
              id: value.id,
              title: value.patient,
              start: value.scheduled_at,
              end: value.scheduled_end,
              location: value.room,
              notes: value.notes,
              treatment: value.treatment,
              diagnostic: value.diagnostic,
              author: value.author,
              patient: value.patient,
              phone: value.phone,
              className: ['b-l b-2x ' + randomClass[random]]
            });
          });
        });
        $scope.eventSources = [events];
      };
      $scope.loadSchedule();
    }
  ]).controller("TimelineCtrl", [
    "$scope", "$location", "$routeParams", "timelineResource", "PERPAGE", function($scope, $location, $routeParams, timelineResource, PERPAGE) {
      var id;
      $scope.perpage = PERPAGE;
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      id = $routeParams.id;
      timelineResource.search({
        user_id: id
      }).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          sort: $scope.sort,
          order: $scope.order
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      return $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
    }
  ]).controller("CashCtrl", [
    "$scope", "$rootScope", "$modal", "$location", "$routeParams", "movementsResource", "PERPAGE", function($scope, $rootScope, $modal, $location, $routeParams, movementsResource, PERPAGE) {
      var id;
      id = $routeParams.id;
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.limit = $location.search().limit || "";
      $scope.order = $location.search().order || "";
      $scope.status = $location.search().status || "";
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      movementsResource.search(_.extend($location.search(), {
        user_id: id
      })).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.dateOptions = {
        startingDay: 1,
        showWeeks: "false"
      };
      $scope.open = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
      };
      $scope.choiceMovements = function(movements) {
        return $scope.movements = movements;
      };
      $scope.chargebond = function() {
        var $parentScope, modal, spendingDialogController;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/chargebond.html",
          controller: spendingDialogController = [
            "$scope", "$modalInstance", "movementsResource", "bondResource", function($scope, $modalInstance, movementsResource, bondResource) {
              $scope.movements = $parentScope.movements;
              $scope.charge = {
                id: ""
              };
              $scope.bond = {
                id: ""
              };
              bondResource.search(_.extend($location.search(), {
                user_id: id
              })).success(function(payload) {
                $scope.bonds = payload.data;
              });
              $scope.submit = function() {
                movementsResource.chargebond(_.extend($scope.charge, {
                  movements: $scope.movements
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Spending has been created successfully!");
                  return $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(spending) {
          if (spending) {
            movementsResource.search(_.extend($location.search(), {
              user_id: id
            })).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
      $scope.charge = function() {
        var $parentScope, modal, spendingDialogController;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/charge.html",
          controller: spendingDialogController = [
            "$scope", "$modalInstance", "movementsResource", function($scope, $modalInstance, movementsResource) {
              $scope.movements = $parentScope.movements;
              $scope.charge = {
                id: ""
              };
              $scope.submit = function() {
                movementsResource.charge(_.extend($scope.charge, {
                  movements: $scope.movements
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Spending has been created successfully!");
                  return $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(charge) {
          if (charge) {
            movementsResource.search(_.extend($location.search(), {
              user_id: id
            })).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
      $scope.devolution = function() {
        var $parentScope, devolutionDialogController, modal;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/devolution.html",
          controller: devolutionDialogController = [
            "$scope", "$modalInstance", "movementsResource", function($scope, $modalInstance, movementsResource) {
              $scope.movements = $parentScope.movements;
              $scope.devolution = {
                id: ""
              };
              $scope.submit = function() {
                movementsResource.devolution(_.extend($scope.devolution, {
                  movements: $scope.movements
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Devolution has been created successfully!");
                  return $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                return $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(devolution) {
          if (devolution) {
            movementsResource.search(_.extend($location.search(), {
              user_id: id
            })).success(function(payload) {
              return $scope.results = payload;
            });
          }
        });
      };
    }
  ]).controller("CashStatisticsCtrl", [
    "$scope", "$location", "movementsResource", function($scope, $location, movementsResource) {
      var bond, card, lineChart1, money, negative, positive;
      $scope.today = new Date();
      $scope.end = $location.search().end || "";
      $scope.begin = $location.search().begin || "";
      $scope.dateOptions = {
        startingDay: 1,
        showWeeks: "false"
      };
      $scope.open = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
      };
      $scope.dateFilter = function() {
        if ($scope.end && $scope.begin) {
          if ($scope.begin > $scope.end) {
            $location.search({
              begin: $scope.end,
              end: $scope.begin
            });
          } else {
            $location.search({
              begin: $scope.begin,
              end: $scope.end
            });
          }
        }
      };
      $scope.dateLength = function() {
        var begin, end;
        end = new Date($scope.end);
        begin = new Date($scope.begin);
        return end.getDate() - begin.getDate();
      };
      $scope.data1 = $scope.data2 = $scope.data3 = [];
      card = money = 0;
      lineChart1 = {};
      lineChart1.data1 = [[1, 15], [2, 20], [3, 14], [4, 10], [5, 10], [6, 20], [7, 28], [8, 26], [9, 22], [10, 23], [11, 24]];
      lineChart1.data2 = [[1, 9], [2, 15], [3, 17], [4, 21], [5, 16], [6, 15], [7, 13], [8, 15], [9, 29], [10, 21], [11, 29]];
      $scope.lineChart = [
        {
          data: lineChart1.data1,
          label: 'Positive cash'
        }, {
          data: lineChart1.data2,
          label: 'Negative cash',
          lines: {
            fill: false
          }
        }, {
          data: lineChart1.data2,
          label: 'Positive - Negative cash',
          lines: {
            fill: false
          }
        }
      ];
      card = money = bond = positive = negative = 0;
      $scope.donutChart = $scope.lineChart = [];
      movementsResource.search($location.search()).success(function(payload) {
        var i, length, results, _i;
        length = $scope.dateLength();
        for (_i = length.length - 1; _i >= 0; _i += -1) {
          i = length[_i];
          $scope.data1.push([i, i]);
        }
        $scope.results = results = payload;
        angular.forEach(results.data, function(value) {
          if (value.payment_type === 'card') {
            card += parseFloat(value.amount);
          }
          if (value.payment_type === 'bond') {
            bond += parseFloat(value.amount);
          }
          if (value.payment_type === 'money') {
            money += parseFloat(value.amount);
          }
          if (value.amount > 0) {
            positive += parseFloat(value.amount);
          }
          if (value.amount < 0) {
            negative += parseFloat(value.amount);
          }
        });
        $scope.lineChart = [
          {
            data: positive,
            label: 'Positive cash'
          }, {
            data: Math.abs(negative),
            label: 'Negative cash'
          }, {
            data: Math.abs(negative + positive),
            label: 'Positive - Negative'
          }
        ];
        return $scope.donutChart = [
          {
            label: " Positivie Card",
            data: Math.abs(card)
          }, {
            label: " Positivie Cash",
            data: Math.abs(money)
          }
        ];
      });
    }
  ]).controller("MovementsCtrl", [
    "$scope", "$rootScope", "$modal", "$location", "movementsResource", "PERPAGE", function($scope, $rootScope, $modal, $location, movementsResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.limit = $location.search().limit || "";
      $scope.order = $location.search().order || "";
      $scope.status = $location.search().status || "";
      $scope.date = $location.search().date || "";
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope.dateFilter = function() {
        $location.search(_.extend($location.search(), {
          date: $scope.date
        }));
      };
      movementsResource.search($location.search()).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.dateOptions = {
        startingDay: 1,
        showWeeks: "false"
      };
      $scope.open = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
      };
      $scope.choiceMovements = function(movements) {
        return $scope.movements = movements;
      };
      $scope.spending = function() {
        var $parentScope, modal, spendingDialogController;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/spending.html",
          controller: spendingDialogController = [
            "$scope", "$modalInstance", "movementsResource", function($scope, $modalInstance, movementsResource) {
              $scope.movements = $parentScope.movements;
              $scope.spending = {
                id: ""
              };
              $scope.submit = function() {
                movementsResource.spending(_.extend($scope.spending, {
                  movements: $scope.movements
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Spending has been created successfully!");
                  return $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(spending) {
          if (spending) {
            movementsResource.search($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
      $scope.devolution = function() {
        var $parentScope, devolutionDialogController, modal;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/devolution.html",
          controller: devolutionDialogController = [
            "$scope", "$modalInstance", "movementsResource", function($scope, $modalInstance, movementsResource) {
              $scope.movements = $parentScope.movements;
              $scope.devolution = {
                id: "",
                amount: $parentScope.movements.amount || "",
                payment_type: $parentScope.movements.payment_type || ""
              };
              $scope.submit = function() {
                movementsResource.devolution(_.extend($scope.devolution, {
                  movements: $parentScope.movements
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Devolution has been created successfully!");
                  return $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                return $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(devolution) {
          if (devolution) {
            movementsResource.search($location.search()).success(function(payload) {
              return $scope.results = payload;
            });
          }
        });
      };
      $scope.viewInvoice = function(invoice) {
        var modal, viewInvoiceDialogController;
        return modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/invoice.html",
          controller: viewInvoiceDialogController = [
            "$scope", "$modalInstance", function($scope, $modalInstance) {
              $scope.invoice = invoice;
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
      };
    }
  ]).controller("AppointmentsCtrl", [
    "$scope", "$rootScope", "$modal", "$routeParams", "prescriptionResource", "roomResource", "sessionResource", "userResource", function($scope, $rootScope, $modal, $routeParams, prescriptionResource, roomResource, sessionResource, userResource) {
      var id;
      id = $routeParams.id;
      prescriptionResource.search({
        user_id: id
      }).success(function(payload) {
        return $scope.prescriptions = payload.data;
      });
      roomResource.search({
        status: 1
      }).success(function(payload) {
        return $scope.rooms = payload.data;
      });
      userResource.get(id, {}).success(function(payload) {
        return $scope.user = payload;
      });
    }
  ]).controller("DiagnosticCtrl", [
    "$rootScope", "$modal", "$scope", "$location", "$routeParams", "roomResource", "prescriptionResource", "pathologyResource", "refererResource", "searchResource", "diagnosticResource", "reviewResource", "treatmentResource", function($rootScope, $modal, $scope, $location, $routeParams, roomResource, prescriptionResource, pathologyResource, refererResource, searchResource, diagnosticResource, reviewResource, treatmentResource) {
      var id;
      id = $routeParams.id;
      $scope.showlist = [];
      if (parseInt(id)) {
        diagnosticResource.get(id).success(function(payload) {
          $scope.diagnostic = payload;
          $scope.reset = angular.copy($scope.diagnostic);
        });
      } else {
        $scope.diagnostic = {
          name: ""
        };
        $scope.reset = angular.copy($scope.diagnostic);
      }
      roomResource.search({
        status: 1
      }).success(function(payload) {
        return $scope.rooms = payload.data;
      });
      searchResource.users({
        'role': "doctor"
      }).success(function(payload) {
        return $scope.doctors = payload.data;
      });
      pathologyResource.search({}).success(function(payload) {
        return $scope.pathologies = payload.data;
      });
      refererResource.search({
        status: 1
      }).success(function(payload) {
        return $scope.referers = payload.data;
      });
      treatmentResource.search({
        active: 1
      }).success(function(payload) {
        return $scope.treatments = payload.data;
      });
      $scope.refesh = function() {
        $scope.showlist = [];
        prescriptionResource.search({
          diagnostic_id: id
        }).success(function(payload) {
          $scope.prescriptions = payload.data;
          angular.forEach($scope.prescriptions, function(obj) {
            return $scope.showlist.push(obj);
          });
          return _.sortBy($scope.showlist, function(o) {
            return o.created_at;
          });
        });
        reviewResource.search({
          diagnostic_id: id
        }).success(function(payload) {
          $scope.reviews = payload.data;
          angular.forEach($scope.reviews, function(obj) {
            return $scope.showlist.push(obj);
          });
          return _.sortBy($scope.showlist, function(o) {
            return o.created_at;
          });
        });
      };
      $scope.addReview = function(id) {
        var $parentScope, addreviewDialogController, modal;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/addreview.html",
          controller: addreviewDialogController = [
            "$scope", "$modalInstance", "reviewResource", function($scope, $modalInstance, reviewResource) {
              $scope.diagnostic = angular.copy($parentScope.diagnostic);
              if (parseInt(id)) {
                reviewResource.get(id).success(function(payload) {
                  $scope.review = payload;
                });
              } else {
                $scope.review = {};
              }
              $scope.submit = function() {
                if ($scope.review.id) {
                  reviewResource.update($scope.review.id, $scope.review).success(function(payload) {
                    $rootScope.$broadcast("success", "Diagnostic Review has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                } else {
                  reviewResource.create(_.extend($scope.review, {
                    diagnostic_id: $scope.diagnostic.id
                  })).success(function(payload) {
                    $rootScope.$broadcast("success", "Diagnostic Review has been created successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(review) {
          if (review) {
            $scope.refesh();
            return;
          }
        });
      };
      $scope.deleteReview = function(index) {
        var x;
        x = confirm("Are you sure you want to delete Review?");
        if (x) {
          reviewResource["delete"]($scope.showlist[index].id).success(function(payload) {
            $rootScope.$broadcast("success", "Review has been deleted successfully!");
            return $scope.showlist.splice(index, 1);
          });
        }
      };
      $scope.addPrescription = function(id) {
        var $parentScope, addPrescriptionDialogController, modal;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/addprescription.html",
          controller: addPrescriptionDialogController = [
            "$scope", "$modalInstance", "prescriptionResource", function($scope, $modalInstance, prescriptionResource) {
              $scope.diagnostic = angular.copy($parentScope.diagnostic);
              $scope.treatments = angular.copy($parentScope.treatments);
              $scope.rooms = angular.copy($parentScope.rooms);
              if (parseInt(id)) {
                prescriptionResource.get(id).success(function(payload) {
                  $scope.prescription = payload;
                });
              } else {
                $scope.prescription = {};
              }
              $scope.submit = function() {
                if ($scope.prescription.id) {
                  prescriptionResource.update($scope.prescription.id, $scope.prescription).success(function(payload) {
                    $rootScope.$broadcast("success", "Prescription has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                } else {
                  prescriptionResource.create(_.extend($scope.prescription, {
                    diagnostic_id: $scope.diagnostic.id
                  })).success(function(payload) {
                    $rootScope.$broadcast("success", "Prescription has been created successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(prescription) {
          if (prescription) {
            $scope.refesh();
            return;
          }
        });
      };
      $scope.deletePrescription = function(index) {
        var x;
        x = confirm("Are you sure you want to delete prescription?");
        if (x) {
          prescriptionResource["delete"]($scope.showlist[index].id).success(function(payload) {
            $rootScope.$broadcast("success", "Prescription has been deleted successfully!");
            return $scope.showlist.splice(index, 1);
          });
        }
      };
      $scope.closediagnostic = function(id) {
        var closediagnosticDialogController, modal;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/closediagnostic.html",
          controller: closediagnosticDialogController = [
            "$scope", "$modalInstance", "diagnosticResource", function($scope, $modalInstance, diagnosticResource) {
              $scope.statuses = ["Resolved", "Abandoned by patient", "Abandoned by doctor"];
              if (parseInt(id)) {
                diagnosticResource.get(id).success(function(payload) {
                  $scope.diagnostic = payload;
                });
              } else {
                $scope.diagnostic = {
                  name: ""
                };
              }
              $scope.submit = function() {
                if ($scope.diagnostic.id) {
                  diagnosticResource.update($scope.diagnostic.id, $scope.diagnostic).success(function(payload) {
                    $rootScope.$broadcast("success", "Diagnostic has been updated successfully!");
                    $modalInstance.close();
                  });
                } else {
                  diagnosticResource.create($scope.diagnostic).success(function(payload) {
                    $rootScope.$broadcast("success", "Diagnostic has been created successfully!");
                    $modalInstance.close();
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        modal.result.then(function() {});
      };
      $scope.loadTags = function(val) {
        return tagsResource.search({
          q: val
        }).then(function(payload) {
          var tags;
          tags = [];
          angular.forEach(payload.data.data, function(item) {
            tags.push({
              id: item.id,
              name: item.name
            });
          });
          return tags;
        });
      };
      $scope.dismiss = function() {
        angular.copy($scope.reset, $scope.diagnostic);
      };
      $scope.submit = function() {
        $scope.form.$setDirty();
        if ($scope.form.$valid) {
          if ($scope.diagnostic.id) {
            diagnosticResource.update($scope.diagnostic.id, $scope.diagnostic).success(function(payload) {
              $rootScope.$broadcast("success", "Diagnostic has been updated successfully!");
              $scope.diagnostic = payload;
            });
          } else {
            diagnosticResource.create($scope.diagnostic).success(function(payload) {
              $rootScope.$broadcast("success", "Diagnostic has been created successfully!");
            });
          }
        }
      };
      $scope.refesh();
    }
  ]).controller("DiagnosticsCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "$routeParams", "diagnosticResource", "PERPAGE", function($rootScope, $scope, $location, $modal, $routeParams, diagnosticResource, PERPAGE) {
      var user_id;
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.limit = $location.search().limit || "";
      $scope.order = $location.search().order || "";
      $scope.status = $location.search().status || "";
      user_id = $routeParams.id;
      if (parseInt(user_id)) {
        $scope.user_id = user_id;
      }
      diagnosticResource.search(_.extend($location.search(), {
        uid: user_id
      })).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.openDiagnostic = function(id) {
        var $parentScope, diagnosticDialogController, modal;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/diagnostic.html",
          controller: diagnosticDialogController = [
            "$scope", "$modalInstance", "pathologyResource", "refererResource", "searchResource", "diagnosticResource", "tagsResource", function($scope, $modalInstance, pathologyResource, refererResource, searchResource, diagnosticResource, tagsResource) {
              var uid;
              uid = $parentScope.user_id;
              searchResource.users({
                'role': "doctor"
              }).success(function(payload) {
                return $scope.doctors = payload.data;
              });
              pathologyResource.search({}).success(function(payload) {
                return $scope.pathologies = payload.data;
              });
              refererResource.search({
                status: 1
              }).success(function(payload) {
                return $scope.referers = payload.data;
              });
              if (!parseInt(id)) {
                $scope.diagnostic = {
                  name: "",
                  user_id: uid
                };
              } else {
                diagnosticResource.get(id).success(function(payload) {
                  $scope.diagnostic = payload;
                });
              }
              $scope.loadTags = function(val) {
                return tagsResource.search({
                  q: val
                }).then(function(payload) {
                  var tags;
                  tags = [];
                  angular.forEach(payload.data.data, function(item) {
                    tags.push({
                      id: item.id,
                      name: item.name
                    });
                  });
                  return tags;
                });
              };
              $scope.submit = function() {
                if ($scope.diagnostic.id) {
                  diagnosticResource.update($scope.diagnostic.id, $scope.diagnostic).success(function(payload) {
                    $rootScope.$broadcast("success", "Diagnostic has been updated successfully!");
                    $modalInstance.close();
                  });
                } else {
                  diagnosticResource.create(_.extend($scope.diagnostic, {
                    "user_id": uid
                  })).success(function(payload) {
                    $rootScope.$broadcast("success", "Diagnostic has been created successfully!");
                    $modalInstance.close();
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        modal.result.then(function() {
          diagnosticResource.search($location.search()).success(function(payload) {
            $scope.results = payload;
          });
        });
      };
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          diagnosticResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Diagnostic has been deleted successfully!");
            return diagnosticResource.search($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          });
        }
      };
    }
  ]).controller("UserCtrl", [
    "$rootScope", "$scope", "$routeParams", "$http", "$q", "$timeout", "$modal", "$location", "$fileUploader", "GenderData", "StatusData", "PostalData", "CountryData", "userResource", "roleResource", "groupResource", "refererResource", "tagsResource", "securityService", "DOMAIN", function($rootScope, $scope, $routeParams, $http, $q, $timeout, $modal, $location, $fileUploader, GenderData, StatusData, PostalData, CountryData, userResource, roleResource, groupResource, refererResource, tagsResource, securityService, DOMAIN) {
      var id, uploader;
      id = $routeParams.id;
      if (id === "new") {
        $scope.user = userResource.defaults;
        $scope.reset = angular.copy($scope.user);
      } else {
        if (!parseInt(id)) {
          id = "me";
        }
        userResource.get(id, {}).success(function(payload) {
          $scope.user = payload;
          $scope.reset = angular.copy($scope.user);
        });
      }
      refererResource.search({
        status: 1
      }).success(function(payload) {
        return $scope.referers = payload.data;
      });
      $scope.onZipcode = function(zipcode) {
        var key;
        key = zipcode.substring(0, 2);
        return angular.forEach(PostalData, function(postal) {
          if (postal.key === key) {
            $scope.user.meta.city = postal.name;
          }
        });
      };
      $scope.loadTags = function(val) {
        return tagsResource.search({
          q: val
        }).then(function(payload) {
          var tags;
          tags = [];
          angular.forEach(payload.data.data, function(item) {
            tags.push({
              id: item.id,
              name: item.name
            });
          });
          return tags;
        });
      };
      $scope.dismiss = function() {
        angular.copy($scope.reset, $scope.user);
      };
      $scope.open = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
      };
      $scope.dateOptions = {
        startingDay: 1,
        showWeeks: "false"
      };
      $scope.token = $http.defaults.headers.common["Authorization"];
      $scope.countries = CountryData;
      $scope.postals = PostalData;
      $scope.statuses = StatusData;
      $scope.genders = GenderData;
      $scope.currentUser = securityService.requestCurrentUser();
      groupResource.all().success(function(payload) {
        return $scope.groups = payload;
      });
      roleResource.all().success(function(payload) {
        return $scope.roles = payload;
      });
      $scope.toggleRole = function(obj) {
        var idx;
        idx = $scope.user.role_id.indexOf(obj);
        if (idx > -1) {
          $scope.user.role_id.splice(idx, 1);
        } else {
          $scope.user.role_id.push(obj);
        }
      };
      $scope.roleCheck = function(obj) {
        var i;
        i = 0;
        if ($scope.user.roles) {
          while (i < $scope.user.roles.length) {
            if ($scope.user.roles[i].name === obj.name) {
              return true;
            }
            i++;
          }
        }
      };
      $scope.save = function() {
        if ($scope.readOnly) {
          return;
        }
        $scope.form.$setDirty();
        if ($scope.form.$valid) {
          if ($scope.user.id) {
            if (id === "me") {
              userResource.updateMe($scope.user).success(function(payload) {
                $rootScope.$broadcast("success", "User has been updated successfully!");
                $scope.user = payload;
              });
            } else {
              userResource.update($scope.user.id, $scope.user).success(function(payload) {
                $rootScope.$broadcast("success", "User has been updated successfully!");
                $scope.user = payload;
              });
            }
          } else {
            userResource.create($scope.user).success(function(payload) {
              $rootScope.$broadcast("success", "User has been created successfully!");
            });
          }
        }
      };
      $scope.removeAvatar = function() {
        return $scope.user.meta.profile_img_url = "";
      };
      uploader = $scope.uploader = $fileUploader.create({
        scope: $scope,
        url: DOMAIN + "/api/upload",
        headers: {
          Authorization: $scope.token
        },
        autoUpload: true,
        removeAfterUpload: true,
        formData: [],
        filters: []
      });
      uploader.filters.push(function(item) {
        var type;
        type = (uploader.isHTML5 ? item.type : "/" + item.value.slice(item.value.lastIndexOf(".") + 1));
        type = "|" + type.toLowerCase().slice(type.lastIndexOf("/") + 1) + "|";
        return "|jpg|png|jpeg|bmp|gif|".indexOf(type) !== -1;
      });
      uploader.bind("progressall", function(event, progress) {
        $scope.progress = progress;
        $scope.showprogress = true;
      });
      uploader.bind("complete", function(event, xhr, item, response) {
        $scope.user.meta.profile_img_url = response.payload.url;
        $timeout((function() {
          $scope.showprogress = false;
        }), 3000);
      });
      $scope.avatarModal = function() {
        var $parentScope, modal, userDialogController;
        if ($scope.readOnly) {
          return;
        }
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/user/user-avatar.html",
          controller: userDialogController = [
            "$scope", "$modalInstance", "$timeout", "DOMAIN", function($scope, $modalInstance, $timeout, DOMAIN) {
              $scope.user = angular.copy($parentScope.user);
              $scope.token = $parentScope.token;
              uploader = $scope.uploader = $fileUploader.create({
                scope: $scope,
                url: DOMAIN + "/api/upload",
                headers: {
                  Authorization: $scope.token
                },
                autoUpload: true,
                removeAfterUpload: true,
                formData: [],
                filters: []
              });
              uploader.filters.push(function(item) {
                var type;
                type = (uploader.isHTML5 ? item.type : "/" + item.value.slice(item.value.lastIndexOf(".") + 1));
                type = "|" + type.toLowerCase().slice(type.lastIndexOf("/") + 1) + "|";
                return "|jpg|png|jpeg|bmp|gif|".indexOf(type) !== -1;
              });
              uploader.bind("progressall", function(event, progress) {
                $scope.progress = progress;
                $scope.showprogress = true;
              });
              uploader.bind("complete", function(event, xhr, item, response) {
                $scope.user.meta.profile_img_url = response.payload.url;
                $timeout((function() {
                  $scope.showprogress = false;
                }), 3000);
              });
              $scope.save = function(user) {
                $modalInstance.close(user);
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        modal.result.then(function(user) {
          if (user) {
            $scope.user = user;
            $scope.save();
          }
        });
      };
    }
  ]).controller("AttachmentDiagnosticCtrl", [
    "$rootScope", "$scope", "$location", "$timeout", "$routeParams", "$fileUploader", "$http", "$modal", "attachmentsResource", "tagsResource", "DOMAIN", function($rootScope, $scope, $location, $timeout, $routeParams, $fileUploader, $http, $modal, attachmentsResource, tagsResource, DOMAIN) {
      var uploader;
      $scope.uid = $routeParams.id;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.token = $http.defaults.headers.common["Authorization"];
      attachmentsResource.search({
        "uid": $scope.uid
      }).success(function(payload) {
        return $scope.results = payload;
      });
      uploader = $scope.uploader = $fileUploader.create({
        scope: $scope,
        url: DOMAIN + "/api/upload" + "?uid=" + $scope.uid,
        headers: {
          Authorization: $scope.token
        },
        autoUpload: true,
        removeAfterUpload: true,
        formData: [],
        filters: [],
        uid: $scope.uid
      });
      uploader.bind("progressall", function(event, progress) {
        $scope.progress = progress;
        $scope.showprogress = true;
      });
      uploader.bind("complete", function(event, xhr, item, response) {
        attachmentsResource.search({
          "uid": $scope.uid
        }).success(function(payload) {
          return $scope.results = payload;
        });
        $timeout((function() {
          $scope.showprogress = false;
        }), 3000);
      });
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          attachmentsResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Attachment file has been deleted successfully!");
            return attachmentsResource.search({
              "uid": $scope.uid
            }).success(function(payload) {
              return $scope.results = payload;
            });
          });
        }
      };
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.edit = function(id) {
        var $parentScope, attachmentsDialogController, modal;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/file.html",
          controller: attachmentsDialogController = [
            "$scope", "$modalInstance", "attachmentsResource", function($scope, $modalInstance, attachmentsResource) {
              $scope.attachment = {
                id: ""
              };
              attachmentsResource.get(id, {}).success(function(payload) {
                return $scope.attachment = payload;
              });
              $scope.submit = function() {
                if ($scope.attachment.id) {
                  attachmentsResource.update($scope.attachment.id, $scope.attachment).success(function(payload) {
                    $rootScope.$broadcast("success", "Attachment has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(attachment) {
          if (attachment) {
            attachmentsResource.search({
              "uid": $scope.uid
            }).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
    }
  ]).controller("AttachmentCtrl", [
    "$rootScope", "$scope", "$location", "$timeout", "$routeParams", "$fileUploader", "$http", "$modal", "attachmentsResource", "DOMAIN", function($rootScope, $scope, $location, $timeout, $routeParams, $fileUploader, $http, $modal, attachmentsResource, DOMAIN) {
      var uploader;
      $scope.uid = $routeParams.id;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.token = $http.defaults.headers.common["Authorization"];
      attachmentsResource.search({
        "uid": $scope.uid
      }).success(function(payload) {
        return $scope.results = payload;
      });
      uploader = $scope.uploader = $fileUploader.create({
        scope: $scope,
        url: DOMAIN + "/api/upload" + "?uid=" + $scope.uid,
        headers: {
          Authorization: $scope.token
        },
        autoUpload: true,
        removeAfterUpload: true,
        formData: [],
        filters: [],
        uid: $scope.uid
      });
      uploader.bind("progressall", function(event, progress) {
        $scope.progress = progress;
        $scope.showprogress = true;
      });
      uploader.bind("complete", function(event, xhr, item, response) {
        attachmentsResource.search({
          "uid": $scope.uid
        }).success(function(payload) {
          return $scope.results = payload;
        });
        $timeout((function() {
          $scope.showprogress = false;
        }), 3000);
      });
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          attachmentsResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Attachment file has been deleted successfully!");
            return attachmentsResource.search({
              "uid": $scope.uid
            }).success(function(payload) {
              return $scope.results = payload;
            });
          });
        }
      };
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.edit = function(id) {
        var $parentScope, attachmentsDialogController, modal;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/file.html",
          controller: attachmentsDialogController = [
            "$scope", "$modalInstance", "attachmentsResource", function($scope, $modalInstance, attachmentsResource) {
              $scope.attachment = {
                id: ""
              };
              attachmentsResource.get(id, {}).success(function(payload) {
                return $scope.attachment = payload;
              });
              $scope.submit = function() {
                if ($scope.attachment.id) {
                  attachmentsResource.update($scope.attachment.id, $scope.attachment).success(function(payload) {
                    $rootScope.$broadcast("success", "Attachment has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(attachment) {
          if (attachment) {
            attachmentsResource.search({
              "uid": $scope.uid
            }).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
    }
  ]).controller("PatientsCtrl", [
    "$rootScope", "$scope", "$location", "searchResource", "userResource", "PERPAGE", function($rootScope, $scope, $location, searchResource, userResource, PERPAGE) {
      $scope.q = $location.search().q || "";
      $scope.status = $location.search().status || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.treatment = $location.search().treatment || "";
      $scope.perpage = PERPAGE;
      searchResource.users(_.extend($location.search(), {
        role: "user"
      })).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.undertreatment = function() {
        $location.search(_.extend($location.search(), {
          treatment: $scope.treatment
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q,
          role: "user"
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          userResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Patient has been deleted successfully!");
            return searchResource.users(_.extend($location.search(), {
              role: "user"
            })).success(function(payload) {
              return $scope.results = payload;
            });
          });
        }
      };
      $scope.edit = function(id) {
        $location.path("/patient/" + id);
      };
    }
  ]).controller("UsersCtrl", [
    "$rootScope", "$scope", "$location", "securityService", "searchResource", "userResource", "PERPAGE", function($rootScope, $scope, $location, securityService, searchResource, userResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.role = $location.search().role || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.currentUser = securityService.requestCurrentUser();
      searchResource.users($location.search()).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          role: $scope.role,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q,
          role: $scope.role
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          userResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "User has been deleted successfully!");
            return searchResource.users($location.search()).success(function(payload) {
              return $scope.results = payload;
            });
          });
        }
      };
      $scope.edit = function(id) {
        $location.path("/users/" + id);
      };
    }
  ]).controller('DashboardCtrl', [
    "$rootScope", "$scope", "$modal", "treatmentResource", "diagnosticResource", "roomResource", "sessionResource", function($rootScope, $scope, $modal, treatmentResource, diagnosticResource, roomResource, sessionResource) {
      roomResource.search({
        status: 1
      }).success(function(payload) {
        return $scope.rooms = payload.data;
      });
      diagnosticResource.search({
        status: 1
      }).success(function(payload) {
        return $scope.diagnostics = payload.data;
      });
      treatmentResource.search({
        active: 1
      }).success(function(payload) {
        return $scope.treatments = payload.data;
      });
      sessionResource.search({}).success(function(payload) {
        return $scope.sessions = payload.data;
      });
      return $scope["new"] = function() {
        var $parentScope, modal, scheduleDialogController;
        $parentScope = $scope;
        return modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/phonedate.html",
          controller: scheduleDialogController = [
            "$scope", "$modalInstance", "searchResource", "sessionResource", function($scope, $modalInstance, searchResource, sessionResource) {
              $scope.session = {
                id: ""
              };
              $scope.rooms = $parentScope.rooms;
              $scope.treatments = $parentScope.treatments;
              $scope.diagnostics = $parentScope.diagnostics;
              $scope.mindate = new Date();
              $scope.dateOptions = {
                startingDay: 1,
                showWeeks: "false"
              };
              $scope.getPatient = function(val) {
                return searchResource.users({
                  'role': "user",
                  q: val
                }).then(function(payload) {
                  var patients;
                  patients = [];
                  angular.forEach(payload.data.data, function(item) {
                    patients.push({
                      id: item.id,
                      fullname: item.first_name + ' ' + item.last_name
                    });
                  });
                  return patients;
                });
              };
              $scope.open = function($event) {
                $event.preventDefault();
                $event.stopPropagation();
                $scope.opened = true;
              };
              $scope.loadEmployees = function(treatment) {
                $scope.loadingEmployees = true;
                $scope.employees = [];
                return searchResource.users({
                  'role': treatment.role
                }).success(function(payload) {
                  $scope.employees = payload.data;
                  $scope.loadingEmployees = false;
                });
              };
              $scope.$watch("session.patient", function(patient) {
                if (patient && patient.id) {
                  return $scope.loadDiagnostics(patient.id);
                }
              });
              $scope.loadDiagnostics = function(patient_id) {
                return diagnosticResource.search({
                  uid: patient_id
                }).success(function(payload) {
                  return $scope.diagnostics = payload.data || [];
                });
              };
              $scope.submit = function() {
                if ($scope.session.id) {
                  sessionResource.update($scope.session.id, $scope.session).success(function(payload) {
                    $rootScope.$broadcast("success", "Schedule has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                } else {
                  sessionResource.create(_.extend($scope.session, {})).success(function(payload) {
                    $rootScope.$broadcast("success", "Schedule has been created successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
      };
    }
  ]).controller("RoomsCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "roomResource", "PERPAGE", function($rootScope, $scope, $location, $modal, roomResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      $scope.status = $location.search().status || "";
      roomResource.search($location.search()).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          roomResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Room has been deleted successfully!");
            return roomResource.search($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          });
        }
      };
      $scope.open = function(id) {
        var modal, roomDialogController;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/room.html",
          controller: roomDialogController = [
            "$scope", "$modalInstance", "roomResource", function($scope, $modalInstance, roomResource) {
              if (!parseInt(id)) {
                $scope.room = {
                  name: ""
                };
              } else {
                roomResource.get(id).success(function(payload) {
                  $scope.room = payload;
                });
              }
              $scope.submit = function() {
                if ($scope.room.id) {
                  roomResource.update($scope.room.id, $scope.room).success(function(payload) {
                    $rootScope.$broadcast("success", "Room has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                } else {
                  roomResource.create($scope.room).success(function(payload) {
                    $rootScope.$broadcast("success", "Room has been created successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        modal.result.then(function(room) {
          if (room) {
            roomResource.search($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
      $scope.setStatus = function(status, rooms) {
        angular.forEach(rooms, function(room) {
          roomResource.update(room.id, {
            status: status
          }).success(function(payload) {
            $location.search(_.extend($location.search(), {
              refresh: (new Date()).getTime()
            }));
          });
        });
      };
    }
  ]).controller("NewsCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "newsResource", "PERPAGE", function($rootScope, $scope, $location, $modal, newsResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      $scope.title = $location.search().title || "";
      newsResource.search($location.search()).success(function(payload) {
        $scope.results = payload;
        $scope.lastNews = payload.data[0];
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          newsResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Article has been deleted successfully!");
            return newsResource.search($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          });
        }
      };
      $scope.open = function(id) {
        var modal, newsDialogController;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          size: 'lg',
          templateUrl: "html/modal/news.html",
          controller: newsDialogController = [
            "$scope", "$modalInstance", "newsResource", function($scope, $modalInstance, newsResource) {
              if (!parseInt(id)) {
                $scope.news = {
                  title: ""
                };
              } else {
                newsResource.get(id).success(function(payload) {
                  $scope.news = payload;
                });
              }
              $scope.submit = function() {
                if ($scope.news.id) {
                  newsResource.update($scope.news.id, $scope.news).success(function(payload) {
                    $rootScope.$broadcast("success", "Article has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                } else {
                  newsResource.create($scope.news).success(function(payload) {
                    $rootScope.$broadcast("success", "Article has been created successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        modal.result.then(function(news) {
          newsResource.search($location.search()).success(function(payload) {
            $scope.results = payload;
          });
        });
      };
      return $scope.read = function(news) {
        var modal, newsReadDialogController;
        return modal = $modal.open({
          backdrop: true,
          keyboard: true,
          size: 'lg',
          templateUrl: "html/modal/news-read.html",
          controller: newsReadDialogController = [
            "$scope", "$modalInstance", function($scope, $modalInstance) {
              $scope.news = news;
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
      };
    }
  ]).controller("ReferersCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "refererResource", "PERPAGE", function($rootScope, $scope, $location, $modal, refererResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.limit = $location.search().limit || "";
      $scope.order = $location.search().order || "";
      $scope.status = $location.search().status || "";
      refererResource.search($location.search()).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          refererResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Referer has been deleted successfully!");
            return refererResource.search($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          });
        }
      };
      $scope.open = function(id) {
        var modal, refererDialogController;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/referer.html",
          controller: refererDialogController = [
            "$scope", "$modalInstance", "refererResource", function($scope, $modalInstance, refererResource) {
              if (!parseInt(id)) {
                $scope.referer = {
                  name: ""
                };
              } else {
                refererResource.get(id).success(function(payload) {
                  $scope.referer = payload;
                });
              }
              $scope.submit = function() {
                if ($scope.referer.id) {
                  refererResource.update($scope.referer.id, $scope.referer).success(function(payload) {
                    $rootScope.$broadcast("success", "Referer has been updated successfully!");
                    $modalInstance.close();
                  });
                } else {
                  refererResource.create($scope.referer).success(function(payload) {
                    $rootScope.$broadcast("success", "Referer has been created successfully!");
                    $modalInstance.close();
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        modal.result.then(function() {
          refererResource.search($location.search()).success(function(payload) {
            $scope.results = payload;
          });
        });
      };
    }
  ]).controller("TreatmentsCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "treatmentResource", "PERPAGE", function($rootScope, $scope, $location, $modal, treatmentResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.status = $location.search().status || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      treatmentResource.search($location.search()).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          treatmentResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Treatment has been deleted successfully!");
            return treatmentResource.search($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          });
        }
      };
      $scope.open = function(id) {
        var modal, treatmentDialogController;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/treatment.html",
          controller: treatmentDialogController = [
            "$scope", "$modalInstance", "treatmentResource", function($scope, $modalInstance, treatmentResource) {
              $scope.roles = ["doctor", "therapist"];
              if (!parseInt(id)) {
                $scope.treatment = {
                  name: ""
                };
              } else {
                treatmentResource.get(id).success(function(payload) {
                  $scope.treatment = payload;
                });
              }
              $scope.submit = function() {
                if ($scope.treatment.id) {
                  treatmentResource.update($scope.treatment.id, $scope.treatment).success(function(payload) {
                    $rootScope.$broadcast("success", "Treatment has been updated successfully!");
                    $modalInstance.close();
                  });
                } else {
                  treatmentResource.create($scope.treatment).success(function(payload) {
                    $rootScope.$broadcast("success", "Treatment has been created successfully!");
                    $modalInstance.close();
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        modal.result.then(function() {
          treatmentResource.search($location.search()).success(function(payload) {
            $scope.results = payload;
          });
        });
      };
      $scope.setStatus = function(status, treatments) {
        angular.forEach(treatments, function(treatment) {
          treatmentResource.update(treatment.id, {
            status: status
          }).success(function(payload) {
            $location.search(_.extend($location.search(), {
              refresh: (new Date()).getTime()
            }));
          });
        });
      };
    }
  ]).controller("PatientBondCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "$routeParams", "bondResource", "userResource", function($rootScope, $scope, $location, $modal, $routeParams, bondResource, userResource) {
      var id;
      id = $routeParams.id;
      if (!parseInt(id)) {
        id = "me";
        userResource.get(id, {}).success(function(payload) {
          $scope.user = payload;
        });
      }
      $scope.q = $location.search().q || "";
      $scope.status = $location.search().status || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      bondResource.search(_.extend($location.search(), {
        user_id: id
      })).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          user_id: id,
          page: page
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope.remove = function(bid) {
        var x;
        x = confirm("Are you sure you want to remove?");
        if (x) {
          bondResource.remove(bid).success(function(payload) {
            $rootScope.$broadcast("success", "Bond has been removed successfully!");
            bondResource.search(_.extend($location.search(), {
              user_id: id
            })).success(function(payload) {
              $scope.results = payload;
            });
          });
        }
      };
      $scope.associatebond = function() {
        var bondDialogController, modal;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/associatebond.html",
          controller: bondDialogController = [
            "$scope", "$modalInstance", "bondResource", function($scope, $modalInstance, bondResource) {
              bondResource.search({
                user_id: id
              }).success(function(payload) {
                $scope.bonds = payload.data;
              });
              $scope.submit = function() {
                bondResource.associate(_.extend($scope.bond, {
                  user_id: id
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Bond has been associated successfully!");
                  $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(bond) {
          if (bond) {
            bondResource.search(_.extend($location.search(), {
              user_id: id
            })).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
      $scope["new"] = function(id) {
        var bondDialogController, modal;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/patientbond.html",
          controller: bondDialogController = [
            "$scope", "$modalInstance", "bondResource", function($scope, $modalInstance, bondResource) {
              $scope.bond = {
                id: ""
              };
              bondResource.bondtype({
                status: 1
              }).success(function(payload) {
                $scope.bonds = payload.data;
              });
              $scope.submit = function() {
                bondResource.add({
                  user_id: id,
                  bond: $scope.bond
                }).success(function(payload) {
                  $rootScope.$broadcast("success", "Bond has been added successfully!");
                  $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(bond) {
          if (bond) {
            bondResource.search(_.extend($location.search(), {
              user_id: id
            })).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
    }
  ]).controller("PatientAmendmentCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "$routeParams", "invoiceResource", "userResource", function($rootScope, $scope, $location, $modal, $routeParams, invoiceResource, userResource) {
      var id;
      id = $routeParams.id;
      if (!parseInt(id)) {
        id = "me";
        userResource.get(id, {}).success(function(payload) {
          $scope.user = payload;
        });
      }
      $scope.q = $location.search().q || "";
      $scope.status = $location.search().status || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      invoiceResource.getInvoice(_.extend($location.search(), {
        user_id: id
      })).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        return $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          user_id: id,
          page: page
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        return $location.search({
          q: $scope.q
        });
      };
    }
  ]).controller("PatientInvoicesCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "$routeParams", "bondResource", "invoiceResource", "userResource", "PERPAGE", function($rootScope, $scope, $location, $modal, $routeParams, bondResource, invoiceResource, userResource, PERPAGE) {
      var id;
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.status = $location.search().status || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      id = $routeParams.id;
      if (!parseInt(id)) {
        id = "me";
      }
      userResource.get(id, {}).success(function(payload) {
        $scope.user = payload;
      });
      bondResource.search(_.extend($location.search(), {
        user_id: id
      })).success(function(payload) {
        return $scope.bonds = payload;
      });
      invoiceResource.getInvoice(_.extend($location.search(), {
        user_id: id
      })).success(function(payload) {
        return $scope.invoices = payload;
      });
      invoiceResource.getAmendments(_.extend($location.search(), {
        user_id: id
      })).success(function(payload) {
        return $scope.amendments = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          user_id: id,
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope.choiceInvoice = function(invoice) {
        return $scope.amendmentinvoice = invoice;
      };
      $scope.doAmendment = function() {
        var $parentScope, amendmentDialogController, modal;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/amendment.html",
          controller: amendmentDialogController = [
            "$scope", "$modalInstance", "invoiceResource", function($scope, $modalInstance, invoiceResource) {
              $scope.invoice = $parentScope.amendmentinvoice;
              $scope.submit = function() {
                invoiceResource.sendAmendments(_.extend($scope.amendment, {
                  invoice: $scope.invoice
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Amendment has been sent successfully!");
                  $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                return $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(amendment) {
          if (amendment) {
            invoiceResource.getAmendments(_.extend($location.search(), {
              user_id: id
            })).success(function(payload) {
              $scope.amendments = payload;
            });
          }
        });
      };
      $scope.choiceBond = function(bond) {
        return $scope.sendinvoice = bond;
      };
      $scope.sendInvoice = function() {
        var $parentScope, modal, sentinvoiceDialogController;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/sentinvoice.html",
          controller: sentinvoiceDialogController = [
            "$scope", "$modalInstance", "invoiceResource", function($scope, $modalInstance, invoiceResource) {
              $scope.bond = $parentScope.sendinvoice;
              $scope.invoice = {
                name: $parentScope.user.meta.first_name + " " + $parentScope.user.meta.last_name,
                address: $parentScope.user.meta.address1,
                fiscalcode: $parentScope.user.meta.national_id
              };
              $scope.submit = function() {
                invoiceResource.sendInvoice(_.extend($scope.invoice, {
                  bond: $scope.bond
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Sent has been sent successfully!");
                  $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                return $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(invoice) {
          if (invoice) {
            invoiceResource.getInvoice(_.extend($location.search(), {
              user_id: id
            })).success(function(payload) {
              $scope.invoices = payload;
            });
          }
        });
      };
      $scope.viewInvoice = function(invoice) {
        var modal, viewInvoiceDialogController;
        return modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/invoice.html",
          controller: viewInvoiceDialogController = [
            "$scope", "$modalInstance", function($scope, $modalInstance) {
              $scope.invoice = invoice;
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
      };
    }
  ]).controller("ClientInvoicesCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "$routeParams", "bondResource", "invoiceResource", "userResource", "PERPAGE", function($rootScope, $scope, $location, $modal, $routeParams, bondResource, invoiceResource, userResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.status = $location.search().status || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      bondResource.search($location.search()).success(function(payload) {
        return $scope.bonds = payload;
      });
      invoiceResource.getInvoice($location.search()).success(function(payload) {
        return $scope.invoices = payload;
      });
      invoiceResource.getAmendments($location.search()).success(function(payload) {
        return $scope.amendments = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope.choiceInvoice = function(invoice) {
        return $scope.amendmentinvoice = invoice;
      };
      $scope.doAmendment = function() {
        var $parentScope, amendmentDialogController, modal;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/amendment.html",
          controller: amendmentDialogController = [
            "$scope", "$modalInstance", "invoiceResource", function($scope, $modalInstance, invoiceResource) {
              $scope.invoice = $parentScope.amendmentinvoice;
              $scope.amendment = {
                amount: $scope.invoice.amount
              };
              $scope.submit = function() {
                invoiceResource.sendAmendments(_.extend($scope.amendment, {
                  invoice: $scope.invoice
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Amendment has been sent successfully!");
                  $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                return $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(amendment) {
          if (amendment) {
            invoiceResource.getAmendments($location.search()).success(function(payload) {
              $scope.amendments = payload;
            });
          }
        });
      };
      $scope.removelast = function() {
        var lastItem, x;
        lastItem = $scope.invoices.data[$scope.invoices.data.length - 1];
        x = confirm("Are you sure you want to delete?");
        if (x) {
          invoiceResource.deleteInvoice(lastItem.id).success(function(payload) {
            $rootScope.$broadcast("success", "Invoice has been deleted successfully!");
            return invoiceResource.getInvoice($location.search()).success(function(payload) {
              $scope.invoices = payload;
            });
          });
        }
      };
      $scope.updateAmount = function() {
        var lastItem, x;
        lastItem = $scope.amendments.data[$scope.amendments.data.length - 1];
        x = confirm("Are you sure you want to delete?");
        if (x) {
          invoiceResource.removeAmendments(lastItem.id, $scope.amendments).success(function(payload) {
            $rootScope.$broadcast("success", "Invoice has been deleted successfully!");
            return invoiceResource.getAmendments($location.search()).success(function(payload) {
              $scope.amendments = payload;
            });
          });
        }
      };
      $scope.choiceBond = function(bond) {
        return $scope.sendinvoice = bond;
      };
      $scope.sendInvoice = function() {
        var $parentScope, modal, sentinvoiceDialogController;
        $parentScope = $scope;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/sentinvoice.html",
          controller: sentinvoiceDialogController = [
            "$scope", "$modalInstance", "invoiceResource", function($scope, $modalInstance, invoiceResource) {
              $scope.bond = $parentScope.sendinvoice;
              $scope.invoice = {
                name: $parentScope.sendinvoice.patient,
                address: $parentScope.sendinvoice.address,
                fiscalcode: $parentScope.sendinvoice.fiscalcode
              };
              $scope.submit = function() {
                invoiceResource.sendInvoice(_.extend($scope.invoice, {
                  bond: $scope.bond
                })).success(function(payload) {
                  $rootScope.$broadcast("success", "Sent has been sent successfully!");
                  $modalInstance.close(payload);
                });
              };
              $scope.dismiss = function() {
                return $modalInstance.close();
              };
            }
          ]
        });
        return modal.result.then(function(invoice) {
          if (invoice) {
            invoiceResource.getInvoice($location.search()).success(function(payload) {
              $scope.invoices = payload;
            });
          }
        });
      };
      $scope.viewInvoice = function(invoice) {
        var modal, viewInvoiceDialogController;
        return modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/invoice.html",
          controller: viewInvoiceDialogController = [
            "$scope", "$modalInstance", function($scope, $modalInstance) {
              $scope.invoice = invoice;
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
      };
    }
  ]).controller("BondsCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "bondResource", "PERPAGE", function($rootScope, $scope, $location, $modal, bondResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      $scope.status = $location.search().status || "";
      bondResource.bondtype($location.search()).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          bondResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Bond has been deleted successfully!");
            return bondResource.bondtype($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          });
        }
      };
      $scope.open = function(id) {
        var bondsDialogController, modal;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/bond.html",
          controller: bondsDialogController = [
            "$scope", "$location", "$modalInstance", "bondResource", function($scope, $location, $modalInstance, bondResource) {
              if (!parseInt(id)) {
                $scope.bond = {
                  name: ""
                };
              } else {
                bondResource.get(id).success(function(payload) {
                  $scope.bond = payload;
                });
              }
              $scope.submit = function() {
                if ($scope.bond.id) {
                  bondResource.update($scope.bond.id, $scope.bond).success(function(payload) {
                    $rootScope.$broadcast("success", "Bond has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                } else {
                  bondResource.create($scope.bond).success(function(payload) {
                    $rootScope.$broadcast("success", "Bond has been created successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        modal.result.then(function(bond) {
          if (bond) {
            bondResource.bondtype($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
    }
  ]).controller("PathologiesCtrl", [
    "$rootScope", "$scope", "$location", "$modal", "pathologyResource", "PERPAGE", function($rootScope, $scope, $location, $modal, pathologyResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      $scope.status = $location.search().status || "";
      pathologyResource.search($location.search()).success(function(payload) {
        return $scope.results = payload;
      });
      $scope.open = function(id) {
        var modal, pathologiesDialogController;
        modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/pathology.html",
          controller: pathologiesDialogController = [
            "$scope", "$modalInstance", "pathologyResource", function($scope, $modalInstance, pathologyResource) {
              if (!parseInt(id)) {
                $scope.pathologie = {
                  name: ""
                };
              } else {
                pathologyResource.get(id).success(function(payload) {
                  $scope.pathologie = payload;
                });
              }
              $scope.submit = function() {
                if ($scope.pathologie.id) {
                  pathologyResource.update($scope.pathologie.id, $scope.pathologie).success(function(payload) {
                    $rootScope.$broadcast("success", "Pathologie has been updated successfully!");
                    $modalInstance.close(payload);
                  });
                } else {
                  pathologyResource.create($scope.pathologie).success(function(payload) {
                    $rootScope.$broadcast("success", "Pathologie has been created successfully!");
                    $modalInstance.close(payload);
                  });
                }
              };
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
        modal.result.then(function(pathologie) {
          if (pathologie) {
            pathologyResource.search($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          }
        });
      };
      $scope.goTo = function(page) {
        $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      $scope.changeLimit = function(limit) {
        $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
      $scope.getTotalPages = function() {
        return $scope.results.last_page;
      };
      $scope.search = function() {
        $location.search({
          q: $scope.q
        });
      };
      $scope["delete"] = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          pathologyResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Pathologie has been deleted successfully!");
            return pathologyResource.search($location.search()).success(function(payload) {
              $scope.results = payload;
            });
          });
        }
      };
    }
  ]).controller("MessagesCtrl", [
    "$scope", "$rootScope", "$modal", "$location", "messageResource", "userResource", "PERPAGE", function($scope, $rootScope, $modal, $location, messageResource, userResource, PERPAGE) {
      $scope.perpage = PERPAGE;
      $scope.q = $location.search().q || "";
      $scope.sort = $location.search().sort || "";
      $scope.order = $location.search().order || "";
      $scope.limit = $location.search().limit || "";
      userResource.getMe().success(function(payload) {
        $scope.user = payload;
        messageResource.search({
          receiver: $scope.user.id
        }).success(function(payload) {
          return $scope.messages = payload;
        });
        return messageResource.search({
          sender: $scope.user.id
        }).success(function(payload) {
          return $scope.sents = payload;
        });
      });
      $scope.open = function(id) {
        var messageDialogController, modal;
        return modal = $modal.open({
          backdrop: true,
          keyboard: true,
          templateUrl: "html/modal/message.html",
          controller: messageDialogController = [
            "$scope", "$modalInstance", "messageResource", function($scope, $modalInstance, messageResource) {
              if (!parseInt(id)) {
                $scope.message = {};
              } else {
                messageResource.get(id).success(function(payload) {
                  $scope.message = payload;
                });
              }
              $scope.dismiss = function() {
                $modalInstance.close();
              };
            }
          ]
        });
      };
      $scope.deleteInbox = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          messageResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Message has been deleted successfully!");
            return messageResource.search({
              receiver: $scope.user.id
            }).success(function(payload) {
              $scope.messages = payload;
            });
          });
        }
      };
      $scope.deleteSent = function(id) {
        var x;
        x = confirm("Are you sure you want to delete?");
        if (x) {
          messageResource["delete"](id).success(function(payload) {
            $rootScope.$broadcast("success", "Message has been deleted successfully!");
            return messageResource.search({
              sender: $scope.user.id
            }).success(function(payload) {
              $scope.sents = payload;
            });
          });
        }
      };
      $scope.search = function() {
        return $location.search({
          q: $scope.q
        });
      };
      $scope.goTo = function(page) {
        return $location.search(_.extend($location.search(), {
          page: page
        }));
      };
      $scope.sortBy = function(sort) {
        if ($scope.sort !== sort) {
          $scope.sort = sort;
          $scope.order = "asc";
        } else {
          $scope.order = ($scope.order === "asc" ? "desc" : "asc");
        }
        return $location.search({
          status: $scope.status,
          sort: $scope.sort,
          order: $scope.order,
          q: $scope.q
        });
      };
      return $scope.changeLimit = function(limit) {
        return $location.search(_.extend($location.search(), {
          limit: limit
        }));
      };
    }
  ]).controller("MessageCtrl", [
    "$scope", "$location", "$rootScope", "$routeParams", "messageResource", function($scope, $location, $rootScope, $routeParams, messageResource) {
      var id;
      $scope.message = {};
      id = $routeParams.id;
      if (!parseInt(id)) {
        $location.path("messages");
      }
      messageResource.get(id).success(function(payload) {
        $scope.message_id = payload;
      });
      return $scope.reply = function() {
        messageResource.create(_.extend($scope.message, {
          receiver: $scope.message_id.sender,
          topic: "Re: " + $scope.message_id.topic
        })).success(function(payload) {
          $rootScope.$broadcast("success", "Message has been send successfully!");
          $location.path("messages");
        });
      };
    }
  ]).controller("ComposeMessageCtrl", [
    "$rootScope", "$scope", "messageResource", "searchResource", function($rootScope, $scope, messageResource, searchResource) {
      $scope.send = function() {
        $scope.form.$setDirty();
        if ($scope.form.$valid) {
          return messageResource.create(_.extend($scope.message, {
            receiver: $scope.receiver.id
          })).success(function(payload) {
            $rootScope.$broadcast("success", "Message has been send successfully!");
            return $scope.message = {};
          });
        }
      };
      $scope.discard = function() {
        return $scope.message = {};
      };
      return $scope.getUsers = function(val) {
        $scope.loadingType = true;
        return searchResource.users({
          q: val
        }).then(function(payload) {
          var users;
          users = [];
          angular.forEach(payload.data.data, function(item) {
            users.push({
              id: item.id,
              fullname: item.first_name + ' ' + item.last_name
            });
          });
          $scope.loadingType = false;
          return users;
        });
      };
    }
  ]).controller("FlashCtrl", [
    "$scope", "$location", "$rootScope", "DELAY", "uniqueIdService", "$sce", function($scope, $location, $rootScope, DELAY, uniqueIdService, $sce) {
      $scope.messages = {};
      $scope.$on("success", function(event, msg) {
        var id;
        id = uniqueIdService.generate();
        $scope.messages[id] = {
          "class": "alert-success",
          msg: $sce.trustAsHtml(msg)
        };
        setTimeout((function() {
          $scope.close(id);
        }), DELAY);
      });
      $scope.$on("notify", function(event, msg) {
        var id;
        id = uniqueIdService.generate();
        $scope.messages[id] = {
          "class": "alert-info",
          msg: $sce.trustAsHtml(msg)
        };
        setTimeout((function() {
          $scope.close(id);
        }), DELAY);
      });
      $scope.$on("warning", function(event, msg) {
        var id;
        id = uniqueIdService.generate();
        $scope.messages[id] = {
          "class": "alert-warning",
          msg: $sce.trustAsHtml(msg)
        };
        setTimeout((function() {
          $scope.close(id);
        }), DELAY);
      });
      $scope.$on("error", function(event, msg) {
        var id;
        id = uniqueIdService.generate();
        $scope.messages[id] = {
          "class": "alert-danger",
          msg: $sce.trustAsHtml(msg)
        };
        setTimeout((function() {
          $scope.close(id);
        }), DELAY);
      });
      $scope.close = function(id) {
        if ($scope.messages.hasOwnProperty(id)) {
          delete $scope.messages[id];
        }
      };
    }
  ]).controller('AccountingStatisticsCtrl', [
    "$scope", "$location", "movementsResource", function($scope, $location, movementsResource) {
      var bond, card, lineChart1, money, receive, sent;
      $scope.today = new Date();
      $scope.end = $location.search().end || "";
      $scope.begin = $location.search().begin || "";
      $scope.dateOptions = {
        startingDay: 1,
        showWeeks: "false"
      };
      $scope.open = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
      };
      $scope.dateFilter = function() {
        if ($scope.end && $scope.begin) {
          if ($scope.begin > $scope.end) {
            $location.search({
              begin: $scope.end,
              end: $scope.begin
            });
          } else {
            $location.search({
              begin: $scope.begin,
              end: $scope.end
            });
          }
        }
      };
      $scope.dateLength = function() {
        var begin, end;
        end = new Date($scope.end);
        begin = new Date($scope.begin);
        return end.getDate() - begin.getDate();
      };
      $scope.data1 = $scope.data2 = $scope.data3 = [];
      lineChart1 = {};
      lineChart1.data1 = [[1, 15], [2, 20], [3, 14], [4, 10], [5, 10], [6, 20], [7, 28], [8, 26], [9, 22], [10, 23], [11, 24]];
      lineChart1.data2 = [[1, 9], [2, 15], [3, 17], [4, 21], [5, 16], [6, 15], [7, 13], [8, 15], [9, 29], [10, 21], [11, 29]];
      $scope.lineChart = [
        {
          data: lineChart1.data1,
          label: 'Invoice Client'
        }, {
          data: lineChart1.data2,
          label: 'Invoice Provider',
          lines: {
            fill: false
          }
        }
      ];
      card = money = bond = receive = sent = 0;
      $scope.donutChart = $scope.lineChart = [];
      movementsResource.search($location.search()).success(function(payload) {
        var i, length, results, _i;
        length = $scope.dateLength();
        for (_i = length.length - 1; _i >= 0; _i += -1) {
          i = length[_i];
          $scope.data1.push([i, i]);
        }
        $scope.results = results = payload;
        angular.forEach(results.data, function(value) {
          if (value.payment_type === 'card' && value.invoices_providers_id !== 0) {
            card += parseFloat(value.amount);
          }
          if (value.payment_type === 'money' && value.invoices_providers_id !== 0) {
            money += parseFloat(value.amount);
          }
          if (value.invoices_providers_id === 0) {
            sent += parseFloat(value.amount);
          }
          if (value.invoices_providers_id !== 0) {
            receive += parseFloat(value.amount);
          }
        });
        $scope.lineChart = [
          {
            data: sent,
            label: 'Invoice Client'
          }, {
            data: receive,
            label: 'Invoice Provider'
          }
        ];
        return $scope.donutChart = [
          {
            label: " Positivie Card",
            data: Math.abs(card)
          }, {
            label: " Positivie Cash",
            data: Math.abs(money)
          }
        ];
      });
    }
  ]);

}).call(this);

//# sourceMappingURL=controllers.js.map
