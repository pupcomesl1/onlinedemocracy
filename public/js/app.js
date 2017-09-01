var _this = this;

$(function () {
    $('a[data-method]').click(function (e) {
        e.preventDefault();
        $.ajax({
            method: $(_this).data('method'),
            url: $(_this).attr('href'),
            headers: {
                'X-XSRF-TOKEN': $('meta[name="csrf-token"]').text()
            }
        }).then(function (data, status, jqXHR) {
            debugger;
            window.location.href = data.redirect;
        });
    });
});