$('#acceptationCookies').on('click', function() {
    const url = $('#route_for_acceptation_cookies').html();
    const data = {};
    axios.post(url, data)
    .then(function (res) {
        $('#bandeau-cookies').hide();
    })
    .catch(function (err) {
        console.log(err.response.statusText)
    })
})

