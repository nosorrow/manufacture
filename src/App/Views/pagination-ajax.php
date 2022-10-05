<div class="container">
    <h1>DataBase AjaxPagination</h1>
    <div class="row">
        <div class="col-md-10">
            <!-- тук js ще покаже резултатът -->
            <div style="min-height: 300px">
                <p class="result"></p>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <!-- тук js ще покаже резултатът -->
            <div style="min-height: 300px">
                <div id="paginator"></div>
            </div>
        </div>
    </div>
</div>
<script>
    function displayPaginated(link) {
        $.get(link, function (data) {
            // показваме link с номерата на страниците
            $('#paginator').html(data.link);
            $.each(data.data, function (key, val) {
                // показваме първите резултати от страницирането
                $(".result").append('<p>' + val.name + '</p>');
            });
        })
    }

    displayPaginated('ajax');

    // При клик на page links
    $(document).on("click", '.page-item a', function (e) {

        e.preventDefault();

        var link, href;

        link = $(this);
        href = link.attr('href');

        $('#paginator ul li').removeClass('active');
        link.parent().addClass('active');
        $('.result').empty();

        displayPaginated(href);
    })

</script>