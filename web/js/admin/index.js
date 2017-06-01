$(document).ready(function () {
    var page = 2; // page step, it's means page | 1 | 2 | 3 etc
    var loading  = false; // prevents multiple loads

    //Ajax load function
    function load_contents(page){
        if(loading == false){
            loading = true;  // set loading flag on

            $.ajax({
                url: '__admin_ajax__',
                data: { page: page},
                type: "post",
                success: function(data){
                    if(data.length == 0){
                        $('#post-table tbody').append("<tr><td colspan='4' class='text-center'>No more posts!</td></tr>");
                        $('.pager').hide();
                        loading = false; // set loading flag off
                    }

                    $('#post-table tbody').append(data);
                    loading = false; // set loading flag off
                },
                error: function(xhr, str)
                {
                    // an error occurred
                    $('#post-table tbody').append("<tr><td colspan='4' class='text-center'>Ops! Something wrong, try again!</td></tr>");
                }
            });

        }
    }

    $('.pager').on('click', 'a', function (event) {
        event.preventDefault();
        load_contents(page); // load content
        page++; // page number increment
    });
});