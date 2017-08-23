$(() => {
    $('a[data-method]').click(e => {
        e.preventDefault();
        $.ajax({
            method: $(this).data('method'),
            url: $(this).attr('href'),
            headers: {
                'X-XSRF-TOKEN': $('meta[name="csrf-token"]').text()
            }
        }).then((data, status, jqXHR) => {
            debugger;
            window.location.href = data.redirect;
        });
    });
});
