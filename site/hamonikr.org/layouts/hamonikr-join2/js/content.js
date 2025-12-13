jQuery(function ($) {
    $(".sidebar-dropdown > a").click(function () {
        $(".sidebar-submenu").slideUp(200);
        if (
            $(this)
            .parent()
            .hasClass("active")
        ) {
            $(".sidebar-dropdown").removeClass("active");
            $(this)
                .parent()
                .removeClass("active");
        } else {
            $(".sidebar-dropdown").removeClass("active");
            $(this)
                .next(".sidebar-submenu")
                .slideDown(200);
            $(this)
                .parent()
                .addClass("active");
        }
    });

    // $(".sidebar-dropdown > a").click(function () {
    //     var menu = $(this).next("ul");
    //     if (menu.is(":visible")) {
    //       //menu.slideUp();
    //       menu.removeClass("current");
    //       menu.hide();
    //     } else {
    //       //menu.slideDown();
    //       menu.addClass("current");
    //       menu.show();
    //     }
    // });

    $("#close-sidebar").click(function () {
        $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function () {
        $(".page-wrapper").addClass("toggled");
    });




});
