var qs = document.querySelector.bind(document);
var qsa = document.querySelectorAll.bind(document); // let dt = null;
// let err = null;

function ajaxRequest(method, url, data) {
  var headers = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
  var callback = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
  var multiform = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : false;
  var internal_headers = {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  };

  if (headers != null) {
    for (var header in headers) {
      internal_headers[header] = headers[header];
    }
  }

  if (!multiform) {
    internal_headers['Content-Type'] = 'application/json; charset=utf-8';
  } else {
    internal_headers['Content-Type'] = 'multipart/form-data; charset=utf-8';
  }

  if (data === null) {
    data = JSON.stringify({});
  }

  axios({
    method: method,
    url: url,
    data: data,
    headers: internal_headers
  }).then(function (response) {
    callback({
      code: response.status,
      response: response.data
    });
  })["catch"](function (error) {
    if (error.response === undefined) {
      callback(null);
    } else {
      callback({
        code: error.response.status,
        response: error.response.data
      });
    }
  }); // axios({
  //         method: method,
  //         url: url,
  //         data: data,
  //         headers: internal_headers,
  //     }
  // )
  //     .then(response => {
  //         dt = response;
  //         console.log(response.response);
  //     }).catch(error => {
  //         err = error;
  //         console.log(error.response);
  // });
}

function ajaxGet(url, data, headers, callback) {
  var multiform = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : false;
  ajaxRequest('GET', url, data, headers, callback, multiform);
}

function ajaxPOST(url, data, headers) {
  var callback = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
  var multiform = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : false;
  ajaxRequest('POST', url, data, headers, callback, multiform);
}

function redirectTo(to) {
  var time = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 5000;

  if (to.trim() === "") {
    return;
  }

  setTimeout(function () {
    window.location.href = to;
  }, time);
}
